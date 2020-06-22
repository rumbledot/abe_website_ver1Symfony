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
        
        $card .= '<div class="card rounded shadow" style="margin:50px;">';
        $card .= '<div class="card-header text-white bg-info mb-3">';
        $card .= '<h4 class="card-title">' . $blog['title'] . '</h4>';
        $card .= '<small class="card-text badge badge-sm badge-warning">'. $blog['statestr'] . '</small></div>';
        
        $card .= '<div class="card-body"><div class="card-text">' . $blog['body'] . '</div></div>';

        $card .= '<ul id="commentList' . $blog['id'] . '" class="list-group" style="margin:10px;"></ul>';

        $card .= '<div class="card-footer">';
        $card .= '<small>' . $blog['updated']->format('d-M-Y') . '</small>';
        $card .= '<button class="comment-add btn btn-warning text-dark btn-md float-right rounded-circle"';
        $card .= 'style="margin:5px;" data-toggle="modal" data-target="#newComment"';
        $card .= 'data-id="' . $blog['id'] . '">';
        $card .= '<i class="fas fa-plus-circle"></i></button>';
        $card .= '<button class="comment-list btn btn-info text-dark btn-md float-right rounded-circle"';
        $card .= ' style="margin:5px;" data-show="hidden"';
        $card .= 'data-id="' . $blog['id'] . '">';
        $card .= '<i class="fa fa-list" aria-hidden="true"></i></button>';
        $card .= '</div></div>';

        return $card;
    }
}