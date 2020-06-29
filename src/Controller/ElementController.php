<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Blog;
use App\Entity\TodoText;
use App\Entity\ListMap;
use App\Entity\ListText;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;

use App\Service\ElementService;

class ElementController extends AbstractController
{
    public function response($code, $data) {
        return array(
            "code" => $code,
            "body" => $data,
        );
    }

    /**
     * @Route("/modal", 
     *      name="_generate_modal",
     *      options = {"expose" = true}))
     * @Method({"GET"})
     */
    public function modalAction(Request $req)
    {
        $type         = $req->get('type');
        
        switch($type) {
            case 'footnote':
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', [ 'type' => 'footnote']));
                break;
            case 'list':
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', [ 'type' => 'list']));
                break;
            case 'picture':
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', [ 'type' => 'picture']));
                break;
            default:
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', [ 'type' => 'error']));
            break;
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/fn/new", 
     *      name="_footnote_new",
     *      options = {"expose" = true}))
     * @Method({"PUT"})
     */
    public function footnoteNewAction(ElementService $es, Request $req)
    {
        $data = array();
        $text = $req->get('data');

        $todo = new TodoText();

        $todo->setText($text);

        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();

        $data['id']     = $todo->getId();
        $data['state']  = $todo->getStatus();
        $data['text']   = $todo->getText();

        $response = $this->response(
                200,
                $this->renderView('modal/element-card.html.twig', [ 
                    'type'      => 'footnote',
                    'status'    => $data['state'], 
                    'text'      => $data['text'], ])
        );
        
        return new JsonResponse($response);
    }

    /**
     * @Route("/fn/change", 
     *      name="_footnote_change",
     *      options = {"expose" = true}))
     * @Method({"GET"})
     */
    public function footnoteChangeAction(Request $req)
    {
        $data   = array();
        $id     = $req->get('id');

        $em     = $this->getDoctrine()->getManager();
        $todo   = $this->getDoctrine()->getRepository(TodoText::class)->findOneBy([
            'id' => $id,
        ]);

        $todo->changeStatus();
        $em->persist($todo);
        $em->flush();

        $data['id']     = $todo->getId();
        $data['state']  = $todo->getStatus();
        $data['text']   = $todo->getText();

        return $data;
    }

    /**
     * @Route("/list/new", 
     *      name="_list_new",
     *      options = {"expose" = true}))
     * @Method({"GET"})
     */
    public function listNewAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        
        $data   = array();
        $lists   = $req->get('data');

        $listMap = new ListMap();
        $em->persist($listMap);
        $em->flush();
        // $listMapId = $listMap->getId();
        $index = 0;
        foreach($lists as $list) {
            $listItem = new ListText();
            $listItem->setText($list);
            $listItem->setStep($index);
            $listItem->setListMap($listMap);
            $em->persist($listItem);
            $em->flush();
            $index++;
        }

        $listDB = '';
        // $listsDB = $listMap->getLists();
        
        $response = $this->response(
                200,
                $this->renderView('modal/element-card.html.twig', [ 
                    'type'      => 'list',
                    'lists'     => $lists,
                    'db'        => $listsDB, ])
        );
        
        return new JsonResponse($response);
    }

}