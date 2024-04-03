<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('blog')]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $color;

    #[ORM\ManyToMany(targetEntity: Blog::class, mappedBy: 'types')]
    private Collection $blogs;

    public function __construct()
    {
        $this->blogs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
    * @return Collection|Blog[]
    */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->addType($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        $this->blogs->removeElement($blog);
        $blog->removeType($this);

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
