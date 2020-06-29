<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Blog;
use App\Entity\TodoText;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

class ElementService
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getTodoData($todo) {
        $data['id']     = $todo->getId();
        $data['state']  = $todo->getStatus();
        $data['text']   = $todo->getText();

        return $data;
    }
}