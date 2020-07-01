<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{

    const USER_STATUS_NORMAL     = 0x0020;
    const USER_STATUS_SUSPENDED  = 0x0010;
    const USER_STATUS_BANNED     = 0x0008;

    const USER_STATE_MAP = array(
        self::USER_STATUS_NORMAL       => "Normal",
        self::USER_STATUS_SUSPENDED    => "Suspended",
        self::USER_STATUS_BANNED       => "Banned",
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="owner")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12, unique=true)
     * @Assert\Length(
     *      min = 4,
     *      max = 12,
     *      allowEmptyString = false
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *      min = 12,
     *      allowEmptyString = false
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $joined;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastlogin;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\OneToOne(
     *      targetEntity="App\Entity\UserProfile"
     * )
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="owner")
     */
    private $blogs;

    public function __construct()
    {
        $this->status       = self::USER_STATUS_NORMAL;
        $this->blogs        = new ArrayCollection();
        $this->joined       = new \DateTime();
        $this->lastlogin    = new \DateTime();
    }
    
    /**
     * @see UserInterface
     */
    public function setLastLogin()
    {
        $this->lastlogin = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // $roles = $this->roles;
        // // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique(array_merge(['ROLE_USER'], $this->roles));
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getJoinedDate()
    {
        return $this->joined;
    }

    public function getLastLoginDate()
    {
        return $this->lastlogin;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusStr()
    {
        return self::USER_STATE_MAP[$this->status];
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatusMap() {
        return self::USER_STATE_MAP;
    }

    public function getStatusMapValue($key) {
        return self::USER_STATE_MAP[$key];
    }

    public function getStatusMapKey($val) {
        return array_search($val, self::USER_STATE_MAP);
    }

    public function getProfile(): ?UserProfile
    {
        return $this->profile;
    }

    public function setProfile(UserProfile $profile): self
    {
        $this->profile = $profile;

        // // set the owning side of the relation if necessary
        // if ($profile->getUserId() !== $this) {
        //     $profile->setUser($this);
        // }

        return $this;
    }

    public function getBlogs()
    {
        return $this->blogs;
    }

    public function getJoined(): ?\DateTimeInterface
    {
        return $this->joined;
    }

    public function setJoined(\DateTimeInterface $joined): self
    {
        $this->joined = $joined;

        return $this;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setOwner($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->contains($blog)) {
            $this->blogs->removeElement($blog);
            // set the owning side to null (unless already changed)
            if ($blog->getOwner() === $this) {
                $blog->setOwner(null);
            }
        }

        return $this;
    }
}