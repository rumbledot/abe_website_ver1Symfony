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

use App\Service\UserService;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="_admin")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction(UserService $us) {
        $users      = $us->getUsers();   

        return array(
            'users' => $users,
        );
    }

    /**
     * @Route("/blogs", name="_admin_blogs")
     * @Method({"GET"})
     * @Template()
     */
    public function blogsAction(UserService $us) {
        $admin_blog = $this->getDoctrine()->getRepository(Blog::class)->findOneBy([
                            'id' => 15,
                        ]);
        $feat       = $us->getBlogData($admin_blog);

        $blogs      = $us->getAdminBlogs();

        $admin      = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                            'username' => 'admin',
                        ]);
        $user       = $us->getUser($admin);

        return array(
            'feat'  => $feat,
            'blogs' => $blogs,
            'user'  => $user,
        );
    }
}