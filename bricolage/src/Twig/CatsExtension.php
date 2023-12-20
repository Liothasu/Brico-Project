<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Category;

class CatsExtension extends AbstractExtension 
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cats', [$this, 'getCategory'])
        ];
    }

    public function getCategory()
    {
        return $this->em->getRepository(Category::class)->findBy([], ['name' => 'ASC']);
    }
}