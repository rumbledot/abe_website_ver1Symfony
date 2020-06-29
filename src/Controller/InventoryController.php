<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InventoryController extends AbstractController
{
    /**
     * @Route("/gallery/{type}", name="_gallery")
     * @Method({"GET"})
     * @Template()
     */
    public function galleryAction($type)
    {
        $images = array();

        switch($type) {
            case 'render':
                $title  = '3D Rendering';
                $images = [ 'images/3d art 01.jpg', 'images/3d art 02.jpg', 'images/3d art 03.jpg', 
                    'images/3d art 04.jpg', 'images/3d art 05.jpg', 'images/3d art 06.jpg', 
                    'images/3d art 07.jpg', 'images/3d art 08.jpg', 'images/3d art 09.jpg' ];
            break;
            case 'project':
                $title  = 'Projects';
                $images = [ 'images/ext.jpg', 'images/ext1.jpg', 'images/ext2.jpg', 
                    'images/ext3.jpg', 'images/ext4.jpg', 'images/ext5.jpg', 
                    'images/ext6.jpg', 'images/ext7.jpg' ];
            break;
            case 'game':
                $title  = 'Hobby';
                $images = [ 'images/Mecha01.jpg', 'images/Mecha02.jpg', 'images/Mecha03.jpg', 
                    'images/Mecha04.jpg', 'images/Mecha05.jpg', ];
            break;
            default:
            break;
        }

        return array (
            'title'     => $title,
            'images'    => $images,
        );
    }
}