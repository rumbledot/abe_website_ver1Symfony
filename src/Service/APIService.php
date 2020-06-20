<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Blog;

use App\Service\CoreHTTPService;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

class APIService {
    
    protected $em;
    protected $API;

    public function __construct(EntityManagerInterface $entityManager, CoreHTTPService $API) {
        $this->em   = $entityManager;
        $this->API  = $API;
    }

    public function getUsers() {
        $data   = array();
        $users  = $this->em->getRepository(User::class)->findAll();

        foreach($users as $user) {
            $id         = $user->getId();
            $user_data  = $this->getUser($id);

            array_push($data, $user_data);
        }

        return $data;
    }
    
    public function getUser($id) {
        $user  = $this->em->getRepository(User::class)->findOneBy(['id', $id]);
            
            $data[$user->getId()]['username']   = $user->getUsername();
            $data[$user->getId()]['email']      = $user->getEmail();
            $data[$user->getId()]['joined']     = $user->getJoinedDate();
            $data[$user->getId()]['lastlogin']  = $user->getLastLoginDate();
            $data[$user->getId()]['status']     = $user->getStatusStr();

            $user_profile           = $user->getProfile();

            $profile['name']        = $user_profile->getFirstName() . " " . $user_profile->getLastName();
            $profile['address']     = $user_profile->getAddress();
            $profile['postcode']    = $user_profile->getPostcode();
            $profile['phone']       = $user_profile->getPhone();
            $profile['mobile']      = $user_profile->getMobile();
            $profile['birthday']    = $user_profile->getBirthday();
            $profile['bio']         = $user_profile->getBio();

            $data[$user->getId()]['profile']    = $profile;

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
            $blog_data = "";
            $blog_data = $this->getBlogData($blog);
            $blog_data[$blog->getId()]['writer']  = $user->getFirstName() + " " + $user->getLastName();
        }

        $data = $blog_data;

        return $data;
    }

    public function getPublicBlogs() {
        $data   = array();

        $blogs  = $this->em->getRepository(Blog::class)->findBy(array(
                'state' => Blog::BLOG_STATE_PUBLIC,
        ));

        foreach($blogs as $blog) {
            $blog_data  = $this->getBlogData($blog);
            $user       = $blog->getOwner();
            $blog_data[$blog->getId()]['writer']  = $user->getFirstName() + " " + $user->getLastName();
        }
    }

    public function getBlogData($blog) {

        $blog_data[$blog->getId()]['title']   = $blog->getTitle();
        $blog_data[$blog->getId()]['body']    = $blog->getBody();
        $blog_data[$blog->getId()]['created'] = $blog->getCreated();
        $blog_data[$blog->getId()]['updated'] = $blog->getUpdated();
        $blog_data[$blog->getId()]['statestr']= $blog->getStateStr();

        return $blog_data;
    }
}