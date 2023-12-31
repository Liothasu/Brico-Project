<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\EventSubscriber;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment implements EventSubscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('comment')]
    private ?int $id;

    #[ORM\Column(type: 'text')]
    #[Groups('comment')]
    private ?string $content;

    #[ORM\Column(type: 'datetime')]
    #[Groups('comment')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups('comment')]
    private \DateTime $updatedAt;

    #[ORM\OneToMany(targetEntity: Dispute::class, mappedBy: 'comment')]
    private ?Collection $disputes;

    #[ORM\ManyToOne(targetEntity: Blog::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private Blog $blog;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?self $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $replies;

    public function __construct(Blog $blog, UserInterface $user)
    {
        $this->blog = $blog;
        $this->user = $user;
        $this->replies = new ArrayCollection();
        $this->disputes = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setcreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getDisputes(): ?Collection
    {
        return $this->disputes;
    }

    /**
     * @param Collection|null $disputes
     */
    public function setDisputes(?Collection $disputes): void
    {
        $this->disputes = $disputes;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function addReply(Comment $comment): self
    {
        if (!$this->replies->contains($comment)) {
            $this->replies->add($comment);
            $comment->setParent($this);
        }

        return $this;
    }

    public function getReplies(): Collection
    {
        return $this->replies;
    }

    #[Groups('comment')]
    public function getUserId(): ?int
    {
        return $this->user?->getId();
    }

    #[Groups('comment')]
    public function getUsername(): ?string
    {
        return $this->user?->getUsername();
    }

    #[Groups('comment')]
    public function getParentId(): ?int
    {
        return $this->parent?->getId();
    }

    public function __toString(): string
    {
        return "{$this->user->getUsername()} {$this->createdAt->format('d/m/y à H:i:s')}";
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return ['prePersist', 'preUpdate'];
    }
}
