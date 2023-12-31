<?php

namespace App\Entity;

use App\Repository\DisputeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisputeRepository::class)]
class Dispute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Order $order_command = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Blog $blog = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'disputes')]
    private ?self $comment = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: self::class)]
    private Collection $disputes;

    public function __construct()
    {
        $this->disputes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOrderCommand(): ?Order
    {
        return $this->order_command;
    }

    public function setOrderCommand(?Order $order_command): static
    {
        $this->order_command = $order_command;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): static
    {
        $this->blog = $blog;

        return $this;
    }

    public function getComment(): ?self
    {
        return $this->comment;
    }

    public function setComment(?self $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDisputes(): Collection
    {
        return $this->disputes;
    }

    public function addDispute(self $dispute): static
    {
        if (!$this->disputes->contains($dispute)) {
            $this->disputes->add($dispute);
            $dispute->setComment($this);
        }

        return $this;
    }

    public function removeDispute(self $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            // set the owning side to null (unless already changed)
            if ($dispute->getComment() === $this) {
                $dispute->setComment(null);
            }
        }

        return $this;
    }
}
