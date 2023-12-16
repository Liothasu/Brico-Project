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
        $supplier->setPhoneNumber('+32477884455');
        $supplier->setEmail('bricoland@gmail.com');
        $supplier->setNumStreet('25 Boulevard Industriel');
        $supplier->setCity('DOUAI');
        $supplier->setPostalCode(59500);
        $supplier->setSlug('bricoland');
        $manager->persist($supplier);

        $supplier2 = new Supplier();
        $supplier2->setNameFactory('Hardouw');
        $supplier2->setPhoneNumber('+3248844555');
        $supplier2->setEmail('Hardouw@gmail.com');
        $supplier2->setNumStreet('45 rue des Gardes');
        $supplier2->setCity('PARIS');
        $supplier2->setPostalCode(75000);
        $supplier2->setSlug('hardouw');
        $manager->persist($supplier2);

        $manager->flush();
    }
}
