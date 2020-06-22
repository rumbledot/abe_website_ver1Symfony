<?php
namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Service\UserService;
use App\Service\APIService;
use App\Services\CoreHTTPService;

class CommentController extends AbstractController
{
    /**
     * @Route("comment/new/",
     *      name    ="_comment_get_list",
     *      options = {"expose" = true})
     * @Method({"GET"})
     */
    public function getListAction(UserService $us, Request $req)
    {
        $id         = $req->get('id');

        $res        = $us->getComments($id);
        
        return new JsonResponse($res);
    }

    /**
     * @Route("comment/add/",
     *      name    ="_comment_add",
     *      options = {"expose" = true})
     * @Method({"PUT"})
     */
    public function addAction(UserService $us, Request $req)
    {
        $id         = $req->get('id');
        $text       = $req->get('text');
        $blog_id    = intval($id);

        $res        = $us->addComments($blog_id, $text);

        return new JsonResponse($res);
    }

    /**
     * @Route("comment/delete/",
     *      name    ="_comment_delete",
     *      options = {"expose" = true})
     * @Method({"DELETE"})
     */
    public function deleteAction(UserService $us, Request $req)
    {
        $id         = $req->get('id');
        $com_id     = intval($id);

        $status     = $us->delComments($com_id);
            
        $res        = $status;
        
        return new JsonResponse($res);
    }
}