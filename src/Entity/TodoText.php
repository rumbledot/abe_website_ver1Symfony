<?php

namespace App\Entity;

use App\Repository\TodoTextRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoTextRepository::class)
 */
class TodoText
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->status    = false;
    }
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
}