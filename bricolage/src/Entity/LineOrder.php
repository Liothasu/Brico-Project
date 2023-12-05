<?php

namespace App\Entity;

use App\Repository\LineOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LineOrderRepository::class)]
class LineOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $sellingPrice = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'lineOrders')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'lineOrders')]
    private ?Order $order_command = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(float $sellingPrice): static
    {
        $this->sellingPrice = $sellingPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

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
}
