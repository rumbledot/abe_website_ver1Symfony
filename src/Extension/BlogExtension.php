<?php
namespace App\Extension;

use App\Entity\Blog;
use App\Entity\User;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BlogExtension extends AbstractExtension
{
    public function __construct(UrlGeneratorInterface $router) {
        $this->router = $router;
    }

    public function getFilters() {
        return [
            new TwigFilter('blog_state', array($this, 'blogStateFunc')),
            new TwigFilter('user_status', array($this, 'userStatusFunc')),
        ];
    }

    public function getFunctions() {
        return array(
            new TwigFunction('card', array($this, 'cardFunc'), array('is_safe' => array('html'))),
        );
    }

    public function blogStateFunc($state) {
        $blog = new Blog();
        return $blog->getStateMapKey($state);
    }

    public function userStatusFunc($state) {
        $user = new User();
        return $user->getStatusMapKey($state);
    }

    public function cardFunc($blog) {
        $card   = '';
        //$url    = $this->router->generateUrl('_get_comments', [ 'blog_ig' => $blog['id'] ]);
        $url    = '#';
        
        $card .= '<div class="card rounded" style="margin:50px;">';
        $card .= '<div class="card-header text-white bg-primary mb-3">';
        $card .= '<h4 class="card-title">' . $blog['title'] . '</h4>';
        $card .= '<p class="card-subtitle"><small class="badge badge-sm badge-primary">'. $blog['statestr'] . '</small></p></div>';
        $card .= '<div class="card-body"><h5 class="card-text">' . $blog['body'] . '</h5></div>';
        $card .= '<div class="card-footer">';
        $card .= '<small>' . $blog['updated']->format('d-M-Y') . '</small>';
        $card .= '<a class="btn btn-warning btn-md float-right rounded-circle" style="margin:5px;" href="' . $url . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $card .= '<a class="btn btn-info btn-md float-right rounded-circle" style="margin:5px;" href=""><i class="fa fa-list" aria-hidden="true"></i></a></div></div>';

        return $card;
    }
}