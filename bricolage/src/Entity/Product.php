<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(length: 50)]
    private ?string $nameProduct = null;

    #[ORM\Column(length: 50)]
    private ?string $color = null;

    #[ORM\Column(length: 50)]
    private ?string $designation = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 50)]
    private ?int $unitPrice;

    #[ORM\Column]
    private ?int $priceVAT = null;

    #[ORM\Column]
    private ?int $vat = null;

    #[ORM\ManyToMany(targetEntity: Promo::class, mappedBy: 'products')]
    private Collection $promos;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Supplier $supplier = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: LineOrder::class)]
    private Collection $lineOrders;

    public function __construct()
    {
        $this->lineOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getNameProduct(): ?string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): static
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

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

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getPriceVAT(): ?int
    {
        return $this->priceVAT;
    }

    public function setPriceVAT(int $priceVAT): static
    {
        $this->priceVAT = $priceVAT;

        return $this;
    }

    public function getVAT(): ?int
    {
        return $this->vat;
    }

    public function setVAT(int $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return Collection<int, Promo>
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): static
    {
        if (!$this->promos->contains($promo)) {
            $this->promos->add($promo);
            $promo->addProduct($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): static
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeProduct($this);
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

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
            $lineOrder->setProduct($this);
        }

        return $this;
    }

    public function removeLineOrder(LineOrder $lineOrder): static
    {
        if ($this->lineOrders->removeElement($lineOrder)) {
            // set the owning side to null (unless already changed)
            if ($lineOrder->getProduct() === $this) {
                $lineOrder->setProduct(null);
            }
        }

        return $this;
    }
}
