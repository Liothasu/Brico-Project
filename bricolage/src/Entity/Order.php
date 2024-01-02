<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $statutOrders = [];

    const ORDER_PENDING = 'PENDING';
    const ORDER_IN_PROCESS = 'IN PROCESS';
    const ORDER_PAID = 'PAID';
    const ORDER_CANCELED = 'CANCELED';

    #[ORM\Column(type:'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $dateOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentMod = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\OneToMany(mappedBy: 'order_command', targetEntity: LineOrder::class)]
    private Collection $lineOrders;

    #[ORM\OneToMany(mappedBy: 'order_command', targetEntity: Dispute::class)]
    private Collection $disputes;

    #[ORM\ManyToOne(targetEntity:User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    public function __construct()
    {
        $this->lineOrders = new ArrayCollection();
        $this->disputes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatutOrders(): array
    {
        $statutOrders = $this->statutOrders;

        $statutOrders[] = 'ORDER_PENDING';
        $statutOrders[] = 'ORDER_IN_PROCESS';
        $statutOrders[] = 'ORDER_PAID';
        $statutOrders[] = 'ORDER_CANCELED';

        return array_unique($statutOrders);
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

    public function getPaymentMod(): ?string
    {
        return $this->paymentMod;
    }

    public function setPaymentMod(string $paymentMod): static
    {
        $this->paymentMod = $paymentMod;

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
     * @return Collection<int, LineOrder>
     */
    public function getLineOrders(): Collection
    {
        return $this->lineOrders;
    }

    public function addLineOrder(LineOrder $lineOrder): static
    {
        if (!$this->lineOrders->contains($lineOrder)) {
            $this->lineOrders->add($lineOrder);
            $lineOrder->setOrderCommand($this);
        }

        return $this;
    }

    public function removeLineOrder(LineOrder $lineOrder): static
    {
        if ($this->lineOrders->removeElement($lineOrder)) {
            // set the owning side to null (unless already changed)
            if ($lineOrder->getOrderCommand() === $this) {
                $lineOrder->setOrderCommand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dispute>
     */
    public function getDisputes(): Collection
    {
        return $this->disputes;
    }

    public function addDispute(Dispute $dispute): static
    {
        if (!$this->disputes->contains($dispute)) {
            $this->disputes->add($dispute);
            $dispute->setOrderCommand($this);
        }

        return $this;
    }

    public function removeDispute(Dispute $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            // set the owning side to null (unless already changed)
            if ($dispute->getOrderCommand() === $this) {
                $dispute->setOrderCommand(null);
            }
        }

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
}
