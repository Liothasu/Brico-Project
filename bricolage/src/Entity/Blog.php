<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use App\Entity\Trait\SlugTrait;
use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(
            normalizationContext: ['groups' => ['blog:patch']],
            denormalizationContext: ['groups' => ['blog:patch']],
            security: "is_granted('ROLE_ADMIN') or object.getAuthor() == user"
        )
    ]
)]
class Blog
{
    use SlugTrait, TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 50)]
    private ?string $title;

    #[ORM\Column(type: 'text')]
    private ?string $featuredText;

    #[ORM\Column(type: 'json')]
    #[Groups(['blog:patch'])]
    private ?string $content;

    #[ORM\ManyToOne(targetEntity: Media::class, cascade: ["remove"])]
    private ?Media $featuredMedia;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'blogs')]
    private Collection $types;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Comment::class, cascade: ["remove"])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Dispute::class)]
    private ?Collection $disputes;

    // Column createdAt and updatedAt

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->types = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->title;
    }

    public function getFeaturedText(): ?string
    {
        return $this->featuredText;
    }

    public function setFeaturedText(?string $featuredText): self
    {
        $this->featuredText = $featuredText;

        return $this;
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

    //getters and setters updatedAt/createdAt

    public function getFeaturedMedia(): ?Media
    {
        return $this->featuredMedia;
    }

    public function setFeaturedMedia(?Media $featuredMedia): self
    {
        $this->featuredMedia = $featuredMedia;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setBlog($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBlog() === $this) {
                $comment->setBlog(null);
            }
        }

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

    public function removeDispute(Dispute $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            // set the owning side to null (unless already changed)
            if ($dispute->getBlog() === $this) {
                $dispute->setBlog(null);
            }
        }

        return $this;
    }
}
