<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $reference;

    #[ORM\Column(length: 50)]
    private string $nameProduct;

    #[ORM\Column(length: 50)]
    private string $color;

    #[ORM\Column(length: 50)]
    private string $designation;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero(message: "Stock can't be negative")]
    private $stock;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private $unitPrice;
    
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private $priceVAT;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class, orphanRemoval: true, cascade: ['persist'])]
    private $images;

    #[ORM\ManyToOne(inversedBy: 'products', cascade: ['persist'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Supplier $supplier = null;

    #[ORM\ManyToMany(targetEntity: Promo::class, mappedBy: 'products')]
    private Collection $promos;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: LineOrder::class)]
    private Collection $lineOrders;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->lineOrders = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getNameProduct(): string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): static
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getDesignation(): string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getPriceVAT(): float
    {
        return $this->priceVAT;
    }

    public function setPriceVAT(float $priceVAT): static
    {
        $this->priceVAT = $priceVAT;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function calculateUnitPrice(): void
    {
        $vatRate = 0.21;
        $this->unitPrice = $this->priceVAT / (1 + $vatRate);
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $images): self
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $images): self
    {
        if ($this->images->removeElement($images)) {
            // set the owning side to null (unless already changed)
            if ($images->getProduct() === $this) {
                $images->setProduct(null);
            }
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

    public function __toString(): string
    {
        return $this->nameProduct;
    }
}
