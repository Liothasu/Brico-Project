<?php

namespace App\Entity;

use App\Repository\DisputeRepository;
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

    #[ORM\Column(type: "string", length: 50)]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 50)]
    private $problemType;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Order $order = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Blog $blog = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    private ?Comment $comment = null;

    public function __construct()
    {

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

    public function getProblemType(): ?string
    {
        return $this->problemType;
    }

    public function setProblemType(string $problemType): self
    {
        $this->problemType = $problemType;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
