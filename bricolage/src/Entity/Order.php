<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private $reference;

    #[ORM\Column(type: 'json')]
    private array $statutOrders = [self::POSSIBLE_STATUSES[0]];
    public const POSSIBLE_STATUSES = ['ORDER_PENDING', 'ORDER_IN_PROCESS', 'ORDER_PAID', 'ORDER_CANCELED'];

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $dateOrder;

    #[ORM\Column(length: 255)]
    private ?string $paymentMode;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private $total;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: LineOrder::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $lineOrders;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Dispute::class)]
    private ?Collection $disputes;

    public function __construct()
    {
        $this->lineOrders = new ArrayCollection();
        $this->disputes = new ArrayCollection();
        $this->dateOrder = new \DateTimeImmutable();
        $this->total = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStatutOrders(): array
    {
        return array_intersect($this->statutOrders, self::POSSIBLE_STATUSES);
    }


    public function setStatutOrders(array $statutOrders): static
    {
        $this->statutOrders = $statutOrders;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeImmutable
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeImmutable $dateOrder): static
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getPaymentMode(): ?string
    {
        return $this->paymentMode;
    }

    public function setPaymentMode(string $paymentMode): static
    {
        $this->paymentMode = $paymentMode;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|LineOrder[]
     */
    public function getLineOrders(): Collection
    {
        return $this->lineOrders;
    }

    public function addLineOrder(LineOrder $lineOrder): static
    {
        if (!$this->lineOrders->contains($lineOrder)) {
            $this->lineOrders->add($lineOrder);
            $lineOrder->setOrder($this);
        }

        return $this;
    }

    public function removeLineOrder(LineOrder $lineOrder): static
    {
        if ($this->lineOrders->removeElement($lineOrder)) {
            // set the owning side to null (unless already changed)
            if ($lineOrder->getOrder() === $this) {
                $lineOrder->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * Remove all line orders associated with the order.
     */
    public function clearLineOrders(): void
    {
        $this->lineOrders->clear();
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

    public function addDispute(Dispute $dispute): static
    {
        if (!$this->disputes->contains($dispute)) {
            $this->disputes->add($dispute);
            $dispute->setOrder($this);
        }

        return $this;
    }

    public function removeDispute(Dispute $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            // set the owning side to null (unless already changed)
            if ($dispute->getOrder() === $this) {
                $dispute->setOrder(null);
            }
        }

        return $this;
    }

    public function getFormattedStatutOrders(): string
    {
        return implode(', ', $this->statutOrders);
    }

    public function containsProduct(Product $product): bool
    {
        foreach ($this->lineOrders as $lineOrder) {
            if ($lineOrder->getProduct() === $product) {
                return true;
            }
        }

        return false;
    }

    public function calculateTotal(): float
    {
        $total = 0.0;

        /** @var LineOrder $lineOrder */
        foreach ($this->lineOrders as $lineOrder) {
            $total += $lineOrder->getSellingPrice() * $lineOrder->getQuantity();
        }

        return $total;
    }

    /**
     * Get a specific LineOrder by product.
     *
     * @param Product $product
     * @return LineOrder|null
     */
    public function getLineOrderByProduct($productId)
    {
        foreach ($this->getLineOrders() as $lineOrder) {
            if ($lineOrder->getProduct()->getId() == $productId) {
                return $lineOrder;
            }
        }

        return null;
    }

    public function __toString()
    {
        return $this->reference;
    }
}
