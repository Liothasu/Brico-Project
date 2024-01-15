<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setReference('lol');
        $product->setNameProduct('Hammer');
        $product->setColor('red');
        $product->setDesignation('Very good!');
        $product->setQuantity(1);
        $product->setUnitPrice(25.99);
        $product->setPriceVAT(10);
        $product->setSlug('hammer');
        $manager->persist($product);

        $product2 = new Product();
        $product2->setReference('lol');
        $product2->setNameProduct('Screwdriver');
        $product2->setColor('pink');
        $product2->setDesignation('I like it');
        $product2->setQuantity(1);
        $product2->setUnitPrice(20.00);
        $product2->setPriceVAT(10);
        $product2->setSlug('screwdriver');
        $manager->persist($product2);

        $manager->flush();

        // $this->addReference('category_1', $product, $product2);
        // $this->addReference('category_2', $product2);
        // $this->addReference('category_3', $product);
    }
}
