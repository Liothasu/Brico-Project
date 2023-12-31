<?php

namespace App\Entity;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $menuOrder;

    #[ORM\ManyToMany(targetEntity: self::class)]
    private $subMenus;

    #[ORM\Column(type: 'boolean')]
    private $isVisible;

    #[ORM\ManyToOne(targetEntity: Blog::class)]
    #[Orm\JoinColumn(onDelete: 'CASCADE')]
    private $blog;

    #[ORM\ManyToOne(targetEntity: Type::class)]
    #[Orm\JoinColumn(onDelete: 'CASCADE')]
    private $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Orm\JoinColumn(onDelete: 'CASCADE')]
    private $link;

    #[ORM\ManyToOne(targetEntity: Page::class)]
    #[Orm\JoinColumn(onDelete: 'CASCADE')]
    private $page;

    public function __construct()
    {
        $this->subMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    public function setMenuOrder(int $menuOrder): self
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubMenus(): Collection
    {
        return $this->subMenus;
    }

    public function addSubMenu(self $subMenu): self
    {
        if (!$this->subMenus->contains($subMenu)) {
            $this->subMenus[] = $subMenu;
        }

        return $this;
    }

    public function removeSubMenu(self $subMenu): self
    {
        $this->subMenus->removeElement($subMenu);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }
}