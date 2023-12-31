<?php

namespace App\DataFixtures;

use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class SupplierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $supplier = new Supplier();
        $supplier->setNameFactory('BricoLand');
        $supplier->setCity('DOUAI');
        $supplier->setSlug('bricoland');
        $manager->persist($supplier);

        $supplier2 = new Supplier();
        $supplier2->setNameFactory('Hardouw');
        $supplier2->setCity('PARIS');
        $supplier2->setSlug('hardouw');
        $manager->persist($supplier2);

        $manager->flush();
    }
}
