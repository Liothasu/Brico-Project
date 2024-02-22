<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $supplier = new Supplier();
        $supplier->setNameFactory('BricoLand');
        $supplier->setCity('DOUAI');
        $supplier->setSlug($this->slugger->slug($supplier->getNameFactory())->lower());
        $manager->persist($supplier);

        $supplier2 = new Supplier();
        $supplier2->setNameFactory('Hardouw');
        $supplier2->setCity('PARIS');
        $supplier2->setSlug($this->slugger->slug($supplier2->getNameFactory())->lower());
        $manager->persist($supplier2);

        $supplier3 = new Supplier();
        $supplier3->setNameFactory('ToolAll');
        $supplier3->setCity('LONDON');
        $supplier3->setSlug($this->slugger->slug($supplier3->getNameFactory())->lower());
        $manager->persist($supplier3);

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

        $product = new Product();
        $product->setReference('lol');
        $product->setNameProduct('Hammer');
        $product->setColor('red');
        $product->setDesignation('Very good!');
        $product->setStock(1);
        $product->setUnitPrice(0);
        $product->setPriceVAT(5);
        $product->setSlug($this->slugger->slug($product->getNameProduct())->lower());
        $product->setCategory($category3);
        $product->setSupplier($supplier3);
        $manager->persist($product);

        $product2 = new Product();
        $product2->setReference('lol');
        $product2->setNameProduct('Screwdriver');
        $product2->setColor('pink');
        $product2->setDesignation('I like it');
        $product2->setStock(1);
        $product2->setUnitPrice(0);
        $product2->setPriceVAT(15);
        $product2->setSlug($this->slugger->slug($product2->getNameProduct())->lower());
        $product2->setCategory($category3);
        $product2->setSupplier($supplier2);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setReference('lol');
        $product3->setNameProduct('Levis Paint');
        $product3->setColor('White');
        $product3->setDesignation('I like it');
        $product3->setStock(1);
        $product3->setUnitPrice(0);
        $product3->setPriceVAT(40);
        $product3->setSlug($this->slugger->slug($product3->getNameProduct())->lower());
        $product3->setCategory($category2);
        $product3->setSupplier($supplier);
        $manager->persist($product3);

        $manager->flush();
    }
}
