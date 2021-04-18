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
        $card  = '';
        
        $card .= '<div class="card rounded shadow" style="margin:20px; max-height:70vh; height:40vh;">';

        // card-floating header
        $card .= '<h5 class="card-title bg-dark text-light rounded" style="width:50%; margin-left:10px; margin-top:-20px; padding:5px;">';
        $card .= $blog['title'];
        $card .= '</h5>';

        // card-body        
        $card .= '<div class="card-body overflow-auto">';
        $card .= '<div class="card-text">' . substr($blog['body'], 0, 150) . ' ...</div>';
        $card .= '</div>';

        // card-footer
        $card .= '<div class="card-footer">';
        $card .= '<button class="btn btn-sm btn-dark">...' . $blog['id'] . '</button>';
        $card .= '</div>';

        $card .= '</div>';

        return $card;
    }
}