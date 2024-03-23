<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $title;

    #[ORM\Column(length: 255)]
    private ?string $content;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $timeMsg;

    #[ORM\Column(type: 'boolean')]
    private $is_read = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "sent")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "received")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recipient;

    #[ORM\ManyToOne(targetEntity: Message::class, inversedBy: "replies", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "original_message_id", nullable: true)]
    private $originalMessage;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'originalMessage')]
    private Collection $replies;

    public function __construct()
    {
        $this->timeMsg = new \DateTimeImmutable();
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTimeMsg(): ?\DateTimeImmutable
    {
        return $this->timeMsg;
    }

    public function setTimeMsg(?\DateTimeImmutable $timeMsg): self
    {
        $this->timeMsg = $timeMsg;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getOriginalMessage(): ?self
    {
        return $this->originalMessage;
    }

    public function setOriginalMessage(?self $originalMessage): self
    {
        $this->originalMessage = $originalMessage;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Message $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setOriginalMessage($this);
        }

        return $this;
    }

    public function removeReply(Message $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            if ($reply->getOriginalMessage() === $this) {
                $reply->setOriginalMessage(null);
            }
        }

        return $this;
    }
}
