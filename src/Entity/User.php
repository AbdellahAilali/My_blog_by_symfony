<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=65)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true,  cascade={"persist"})
     */
    private $comments;

    /**
     * @param string             $id
     * @param string             $firstName
     * @param string             $lastName
     * @param \DateTimeInterface $birthDay
     */
    public function __construct(string $id, string $firstName, string $lastName, \DateTimeInterface $birthDay)
    {
        $this->id = $id;
        $this->firstname = $firstName;
        $this->lastname = $lastName;
        $this->birthday = $birthDay;

        $this->comments = new ArrayCollection();
    }

    /**
     * @param string             $firstName
     * @param string             $lastName
     * @param \DateTimeInterface $birthDay
     */
    public function update(string $firstName, string $lastName, \DateTimeInterface $birthDay)
    {
        $this->firstname = $firstName;
        $this->lastname = $lastName;
        $this->birthday = $birthDay;
    }

    /**
     * @param $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTime $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComments(Comment $comments): self
    {
        if ($this->comments->contains($comments)) {
            $this->comments->removeElement($comments);
            // set the owning side to null (unless already changed)
            if ($comments->getUser() === $this) {
                $comments->setUser(null);
            }
        }

        return $this;
    }




}
