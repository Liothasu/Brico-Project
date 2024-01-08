<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

;

class CategoryFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
            
    }

    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Jardinage');
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        // $category->addProduct($this->getReference('category_1'));
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName('Peinture');
        $category2->setSlug('peinture');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Outillage');
        $category3->setSlug('outillage');
        $manager->persist($category3);

        $manager->flush();
    }
}
