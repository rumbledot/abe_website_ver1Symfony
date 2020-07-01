<?php

namespace App\Entity;

use App\Repository\ListMapRepository;
use Doctrine\Common\Collections\Collection;
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
     *      mappedBy="listMap")
     */
    private $listItem;

    /**
     * Constructor
     */
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

    /**
     * @return Collection|ListText[]
     */
    public function getListItem(): Collection
    {
        return $this->listItem;
    }

    public function addListItem(ListText $listItem): self
    {
        if (!$this->listItem->contains($listItem)) {
            $this->listItem[] = $listItem;
            $listItem->setListMap($this);
        }

        return $this;
    }

    public function removeListItem(ListText $listItem): self
    {
        if ($this->listItem->contains($listItem)) {
            $this->listItem->removeElement($listItem);
            // set the owning side to null (unless already changed)
            if ($listItem->getListMap() === $this) {
                $listItem->setListMap(null);
            }
        }

        return $this;
    }

}