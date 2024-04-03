<?php

namespace App\Entity;

use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromoRepository::class)]
class Promo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $name;

    #[ORM\Column]
    private float $percent;

    #[ORM\Column(type:'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $dateBegin;

    #[ORM\Column(type:'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $dateEnd;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'promos')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPercent(): float
    {
        return $this->percent;
    }

    public function setPercent(float $percent): self
    {
        $this->percent = $percent;
        return $this;
    }

    public function getDateBegin(): \DateTimeImmutable
    {
        return $this->dateBegin;
    }

    public function setDateBegin(\DateTimeImmutable $dateBegin): static
    {
        $this->dateBegin = $dateBegin;

        return $this;
    }

    public function getDateEnd(): \DateTimeImmutable
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeImmutable $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function isactivepromo(): bool
    {
        $now = new \DateTimeImmutable();

        $isActive = $this->dateBegin <= $now && $now <= $this->dateEnd;

        return $isActive;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
