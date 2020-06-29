<?php

namespace App\Entity;

use App\Entity\User;

use App\Repository\BlogsRepository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=BlogsRepository::class)
 */
class Blog
{

    const BLOG_STATE_DRAFT      = 0x0008;
    const BLOG_STATE_PRIVATE    = 0x0010;
    const BLOG_STATE_PUBLIC     = 0x0020;

    const BLOG_STATE_MAP = array(
        "Draft"     =>  self::BLOG_STATE_DRAFT,
        "Private"   =>  self::BLOG_STATE_PRIVATE,
        "Public"    =>  self::BLOG_STATE_PUBLIC,
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\User",
     *      inversedBy="blogs",
     * )
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogMap", mappedBy="blog")
     */
    private $contents;

    /**
     * @ORM\Column(type="smallint")
     */
    private $state;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=4000)
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="blog", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=BlogMap::class, mappedBy="blog", orphanRemoval=true)
     */
    private $lists;
    
    public function __construct()
    {
        $this->state    = self::BLOG_STATE_DRAFT;
        $this->created  = new \DateTime();
        $this->updated  = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setOwnerId(User $user)
    {
        $this->owner = $user;
    }

    public function getOwnerId()
    {
        return $this->owner->getId();
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated()
    {
        $this->updated = new \DateTime();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getStateStr()
    {
        return $this->getStateMapKey($this->state);
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getStateMap()
    {
        return self::BLOG_STATE_MAP;
    }

    public function getStateMapValue($key) {
        return self::BLOG_STATE_MAP[$key];
    }

    public function getStateMapKey($val) {
        return array_search($val, self::BLOG_STATE_MAP);
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBlogId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getBlogId() === $this) {
                $comment->setBlogId(null);
            }
        }

        return $this;
    }
}