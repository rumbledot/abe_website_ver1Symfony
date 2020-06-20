<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UtilJSController extends AbstractController
{
    /**
     * @Route("/form.js", name="_util_js_form")
     * @Method({"GET"})
     */
    public function formAction(Request $req)
    {
        $id = $req->get('id');
        $js = $req->get('js');

        if (! isset($js)) {
            $js = 0;
        }
        // generate code
        $data = $this->renderView('utilJS/form.js.twig', array(
            'id'    => $id,
            'js'    => $js,
        ));

        // response
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/javascript');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        return ($response);
    }
}