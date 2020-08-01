<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Blog;
use App\Entity\TodoText;
use App\Entity\ListMap;
use App\Entity\ListText;
use App\Entity\Picture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Form\Type\BlogType\pictureNewType;

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
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', 
                [ 'type' => 'footnote']));
                break;
            case 'list':
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', 
                [ 'type' => 'list']));
                break;
            case 'picture':
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', 
                [ 'type' => 'picture1']));
                break;
            default:
                $response = $this->response(200, $this->renderView('modal/modal.html.twig', 
                [ 'type' => 'error']));
                break;
        }
        
        return new JsonResponse($response);
    }

    /**
     * @Route("/picture/new/", 
     *      name="_picture_new",
     *      options = {"expose" = true}))
     * @Method({"POST"})
     */
    public function pictureNewAction(Request $req, SluggerInterface $slugger, ElementService $es, ParameterBagInterface $param)
    {
        $path   = $param->get('image_directory');
        $pic    = new Picture();

        $data       = $req->request->get('data');
        $id         = $req->request->get('id');
        $picFile    = $req->request->get('pic');
        $picTitle   = $req->request->get('title');
        $picDesc    = $req->request->get('desc');
        $filename   = $req->request->get('filename');

        
        // $newFilename= $path.'\\'.$filename;
        // file_put_contents('.\images\uploads', $picFile);
        
        // $pic->setImageFile($newFilename);
        // $pic->setTitle($picTitle);
        // $pic->setDescription($picDesc);

        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->persist($pic);
        // $entityManager->flush();

        $picData['title']   = $picTitle;
        $picData['desc']    = $picDesc;
        $picData['filename']= $filename;
        $picData['path']    = $id;
        $picData['data']    = $data;

        $response = $this->response(200, $this->renderView('modal/element-card.html.twig', 
        [ 'type' => 'picture', 'pic' => $picData, ]));

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
        $item   = array();
        $lists   = $req->get('data');

        $listMap = new ListMap();
        $em->persist($listMap);
        $em->flush();
        $id = $listMap->getId();
        
        $index = 0;
        foreach($lists as $list) {
            $listItem = new ListText();
            $listItem->setText($list);
            $listItem->setStep($index);
            $listItem->setListMap($listMap);
            $listMap->addListItem($listItem);
            $em->persist($listItem);
            $em->flush();
            $index++;
        }
        $em->persist($listMap);
        $em->flush();

        // $listsDB = '';

        $listMap = $this->getDoctrine()->getRepository(ListMap::class)->findOneBy([
            'id' => $id,
        ]);

        $listsDB = $listMap->getLists();
        foreach($listsDB as $list) {
            $item                   = array();
            $item['status']         = $list->getStatus();
            $item['text']           = $list->getText();
            $data[$list->getId()]   = $item;
        }
        
        $response = $this->response(
                200,
                $this->renderView('modal/element-card.html.twig', [ 
                    'type'      => 'list',
                    'lists'     => $data, 
                    'listBD'    => $listsDB, ])
        );
        
        return new JsonResponse($response);
    }

    

}