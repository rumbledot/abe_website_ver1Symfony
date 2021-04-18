<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Blog;
use App\Entity\Comment;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

class BlogService {
    
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getBlogs() {
        $data   = array();
        $blogs  = $this->em->getRepository(Blog::class)->findAll();

        foreach($blogs as $blog) {
            $blog_data  = $this->getBlogData($blog);
            $user       = $blog->getOwner();
            $profile    = $user->getProfile();

            $blog_data['writer']  = $profile->getFirstName() . " " . $profile->getLastName();
            $blog_data['ownerID'] = $user->getId();
            array_push($data, $blog_data);
        }
        
        return $data;
    }

    public function getAdminBlogs() {

        $data   = array();
        $qb     = $this->em->createQueryBuilder();
        $user   = $this->em->getRepository(User::class)->findOneBy(['id' => 9]);

            $qb     ->select ('b')
                    ->from('App:Blog', 'b')
                    ->where('b.owner = :owner AND b.state = :state AND b.id != 15')
                    ->setParameter('owner', $user)
                    ->setParameter('state', Blog::BLOG_STATE_PUBLIC)
                    ->orderBy('b.updated', 'DESC');

        $blogs  = $qb->getQuery()->getResult();
        
        foreach($blogs as $blog) {
            $blog_data  = $this->getBlogData($blog);
            $user       = $blog->getOwner();
            $profile    = $user->getProfile();
            
            $blog_data['writer']  = $profile->getFirstName() . " " . $profile->getLastName();
            $blog_data['ownerID'] = $user->getId();
            array_push($data, $blog_data);
        }

        return $data;
    }

    public function getAdminBlog($offset) {

        $data   = array();
        $qb     = $this->em->createQueryBuilder();
        $user   = $this->em->getRepository(User::class)->findOneBy(['id' => 9]);

        $result = $this->em->getRepository(Blog::class)->findBy(
            array(
                'owner' => $user,
            ),
            array(
                'id'    => 'DESC'
            ),
            1,
            $offset
        );

        if (count($result) > 0) {
            $data['blog'] = $this->getBlogData($result[0]);
        }

        $result = $this->em->getRepository(Blog::class)->findBy(
            array(
                'owner' => $user,
            ),
            array(
                'id'    => 'DESC'
            ),
            1,
            $offset + 1
        );

        if (count($result) > 0) {
            $data['next'] = $offset + 1;
        } else {
            $data['next'] = -1;
        }

        if ($offset > 0) {
            $result = $this->em->getRepository(Blog::class)->findBy(
                array(
                    'owner' => $user,
                ),
                array(
                    'id'    => 'DESC'
                ),
                1,
                $offset - 1
            );

            if (count($result) > 0) {
                $data['prev'] = $offset - 1;
            }
        } else {
            $data['prev'] = -1;
        }

        return $data;
    }

    public function getUserBlogs($user) {

        $data   = array();
        $qb     = $this->em->createQueryBuilder();

            $qb     ->select ('b')
                    ->from('App:Blog', 'b')
                    ->where('b.owner = :owner OR b.state = :state')
                    ->setParameter('owner', $user)
                    ->setParameter('state', Blog::BLOG_STATE_PUBLIC)
                    ->orderBy('b.updated', 'DESC');

        $blogs  = $qb->getQuery()->getResult();
        
        foreach($blogs as $blog) {
            $blog_data  = $this->getBlogData($blog);
            $user       = $blog->getOwner();
            $profile    = $user->getProfile();
            
            $blog_data['writer']  = $profile->getFirstName() . " " . $profile->getLastName();
            $blog_data['ownerID'] = $user->getId();
            array_push($data, $blog_data);
        }

        return $data;
    }

    public function getUserBlogsOnly($user) {

        $data   = array();
        $blogs  = $user->getBlogs();
        
        foreach($blogs as $blog) {
            $blog_data  = $this->getBlogData($blog);
            $user       = $blog->getOwner();
            $profile    = $user->getProfile();
            
            $blog_data['writer']  = $profile->getFirstName() . " " . $profile->getLastName();
            $blog_data['ownerID'] = $user->getId();
            array_push($data, $blog_data);
        }

        return $data;
    }

    public function getPublicBlogs() {
        
        $data       = array();

        $blogs  = $this->em->getRepository(Blog::class)->findBy([
                'state' => Blog::BLOG_STATE_PUBLIC
                ]);

        foreach($blogs as $blog) {
            $blog_data  = $this->getBlogData($blog);
            $user       = $blog->getOwner();
            $profile    = $user->getProfile();

            $blog_data['writer']  = $profile->getFirstName() . " " . $profile->getLastName();
            $blog_data['ownerID'] = $user->getId();
            array_push($data, $blog_data);
        }
        
        return $data;
    }

    public function getBlogData($blog) {

        $blog_data['id']        = $blog->getId();
        $blog_data['title']     = $blog->getTitle();
        $blog_data['body']      = $blog->getBody();
        $blog_data['created']   = $blog->getCreated();
        $blog_data['updated']   = $blog->getUpdated();
        $blog_data['statestr']  = $blog->getStateStr();

        return $blog_data;
    }

    public function getComments($id) {
        $data       = array();

        $blog       = $this->em->getRepository(Blog::class)->findOneBy([
                            'id' => $id,
                        ]);

        if ($blog) {
            $cs         = $blog->getComments();
            
            if ($cs) {
                
                if (count($cs) > 0) {
                    foreach ($cs as $c) {
                        $data[$c->getId()] = $c->getText();
                    }
                } else {
                    $data['error'] = "No comment yet";
                }
            } else {
                $data['error'] = "No comment yet";
            }
        } else {
            $data['error'] = "Blog not found";
        }

        return $data;
    }

    public function addComments($id, $text) {
        $data       = array();
        $blog       = $this->em->getRepository(Blog::class)->findOneBy([
                            'id' => $id,
                        ]);
        $comment    = new Comment();
        $textstr    = strval($text);

        try {
            $comment->setBlogId($blog);
            $comment->setText($textstr);
            $this->em->persist($comment);
            $this->em->flush();
            
            $data['service_id'] = $id;
            $data['service_text'] = $text;
            $data['content'] = $textstr;
            $data['status'] = 1;
            return $data;
            
        } catch (Exception $e) {
            $data['status'] = 0;
            $data['error']  = $e;
            return $data;
        }
    }

    public function delComments($id) {
        try {
            $qb     = $this->em->createQueryBuilder();

            $qb     ->select ('c')
                    ->from('App:Comment', 'c')
                    ->where('c.id = :com_id')
                    ->setParameter('com_id', $id);

            $comm   = $qb->getQuery()->getResult();

            foreach ($comm as $c) {
                $this->em->remove($c);
                $this->em->flush();
            }

            return "OK";
        } catch (Exception $e) {
            return "FAILED";
        }
    }

    public function getCommentData($c) {
        $c_data['text']         = $c->getText();
    }
}