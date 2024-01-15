<?php

namespace App\DataFixtures;

use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

;

class SupplierFixtures extends Fixture
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
        $supplier2->setSlug($this->slugger->slug($supplier->getNameFactory())->lower());
        $manager->persist($supplier2);

        $supplier3 = new Supplier();
        $supplier3->setNameFactory('ToolAll');
        $supplier3->setCity('LONDON');
        $supplier3->setSlug($this->slugger->slug($supplier->getNameFactory())->lower());
        $manager->persist($supplier3);

        $manager->flush();

        // $this->addReference('product_1', $supplier, $supplier2 );

    }
}
