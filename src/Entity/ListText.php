<?php

namespace App\Entity;

use App\Repository\ListTextRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\ListMap;

/**
 * @ORM\Entity(repositoryClass=ListTextRepository::class)
 */
class ListText
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $step;    

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\ListMap",
     *      inversedBy="listItem",
     * )
     */
    private $listmap;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function changeStatus()
    {
        $this->status = !$this->status;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function setListMap(ListMap $listMap)
    {
        $this->listMap = $listMap;
    }

    public function getListMap()
    {
        return $this->listMap;
    }
}