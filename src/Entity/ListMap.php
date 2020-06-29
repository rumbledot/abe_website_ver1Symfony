<?php

namespace App\Entity;

use App\Repository\ListMapRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\ListItem;

/**
 * @ORM\Entity(repositoryClass=ListMapRepository::class)
 */
class ListMap
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\ListText",
     *      mappedBy="listmap"
     * )
     */
    private $listItem;

    public function __construct() {
        $this->listItem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLists()
    {
        return $this->listItem;
    }

}