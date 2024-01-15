<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Gardening');
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName('Painting');
        $category2->setSlug($this->slugger->slug($category2->getName())->lower());
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Tools');
        $category3->setSlug($this->slugger->slug($category3->getName())->lower());
        $manager->persist($category3);


        $manager->flush();
    }
}
