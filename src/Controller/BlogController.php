<?php
namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Form\Type\BlogType\blogUpdateType;
use App\Form\Type\BlogType\blogNewType;

use App\Service\UserService;
use App\Service\APIService;
use App\Service\CURLService;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="_blogs")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(UserService $us)
    {
        $em         = $this->get('doctrine')->getManager();
        $user_role  = $this->get('security.token_storage')->getToken()->getRoleNames();
        $curr_user  = $this->get('security.token_storage')->getToken()->getUser();
        
        if (in_array("ROLE_ADMIN", $user_role)) {

            $blogs      = $us->getBlogs();

        } else if (in_array("ROLE_USER", $user_role)) {
            $user       = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'id' => $curr_user->getId(),
                ]);

            $blogs  = $us->getUserBlogs($user);
        } else {
            $admin      = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                            'username' => 'admin',
                        ]);
            $profile    = $us->getUser($admin);
            
            $admin_blog = $this->getDoctrine()->getRepository(Blog::class)->findOneBy([
                            'id' => 15,
                        ]);
            $feat       = $us->getBlogData($admin_blog);

            return $this->render('blog/profile.html.twig', [
                'profile'       => $profile,
                'featured'      => $feat,
            ]);
        }

        // $blogs  = $this->getDoctrine()->getRepository(Blog::class)->findAll();
        
        return array(
            'blogs'  => $blogs,
        );
    }

    /**
     * @Route("/blogs/{id}", name="_user_blogs")
     * @Method({"GET"})
     * @Template
     */
    public function blogsAction(UserService $us, $id)
    {
        $user_data  = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'id' => $id,
        ]);

        $user       = $us->getUser($user_data);
        $blogs      = $us->getUserBlogsOnly($user_data);
        
        return array(
            'user'  => $user,
            'blogs' => $blogs,
        );
    }

    /**
     * @Route("/view/{id}", 
     *      name="_blog_detail",
     *      options = {"expose" = true}))
     * @Method({"GET"})
     * @Template
     */
    public function viewAction(UserService $us, $id)
    {
        $blog       = $this->getDoctrine()->getRepository(Blog::class)->find($id);
        $tags       = $blog->getTags();
        $comments   = $blog->getComments();
        
        return array(
            'blog'      => $blog,
            'tags'      => $tags,
            'comments'  => $comments,
        );
    }

    /**
     * @Route("/new", name="_blog_new")
     * @Method({"PUT"})
     * @Security("is_granted('ROLE_USER')")
     * @Template
     */
    public function newAction(Request $req)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $blog = new Blog();

        $form = $this->createForm(blogNewType::class, $blog, array(
            'states'            => $blog->getStateMap(),
        ));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {
            $blog->setOwnerId($user);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('_blogs');
        }
        
        return array (
            'form'      => $form->createView(),
        );
    }

    /**
     * @Route("/newlist", name="_blog_new_list")
     * @Method({"GET"})
     * @Template
     */
    public function newListAction(Request $req)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $blog = new Blog();

        return array (
            'user'      => $user,
            'states'    => $blog->getStateMap(),
        );
    }

    /**
     * @Route("/update/{id}", name="_blog_update")
     * @Method({"PUT"})
     * @Security("is_granted('ROLE_USER')")
     * @Template
     */
    public function updateAction($id, Request $req)
    {
        $blog = $this->getDoctrine()->getRepository(Blog::class)->find($id);

        $curr_state = $blog->getState();
        $states     = $blog->getStateMap();
        
        $form = $this->createForm(blogUpdateType::class, null, array(
            'curr_state'    => $curr_state,
            'states'        => $states,
            'blog'          => $blog,
        ));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($blog->getState()  != $form->get('state')->getData())   $blog->setState($form->get('state')->getData());
            if ($blog->getTitle()  != $form->get('title')->getData())  $blog->setTitle($form->get('title')->getData());
            if ($blog->getBody()   != $form->get('body')->getData())   $blog->setBody($form->get('body')->getData());

            $blog->setUpdated();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('_blogs');
        }
        
        return array(
            'curr_state'    => $curr_state,
            'states'        => $states,
            'blog'          => $blog, 
            'form'          => $form->createView(),
        );
    }

    /**
     * @Route("/delete/{id}", name="_blog_delete")
     * @Method({"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteAction($id, Request $req)
    {
        $blog = $this->getDoctrine()->getRepository(Blog::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($blog);
        $entityManager->flush();

        return $this->redirectToRoute('_blogs');
    }

    /**
     * @Route("/api", name="_blogs_api")
     * @Method({"GET"})
     */
    public function testAPIAction(CURLService $api)
    {
        $data   = $api->get('posts');
        $posts  = $data['body'];

        $page = '<html><body>from API: ';

        foreach((Array)($posts) as $post) {
            $page .= '<div class="card"><div class="card-header">'.$post['title'].'</div>';
            $page .= '<div class="card-body">'.$post['body'].'</div>';
            $page .= '</div>';
        }
        
        $page .= '</body></html>';
        
        return new Response(
            $page
        );
    }
}