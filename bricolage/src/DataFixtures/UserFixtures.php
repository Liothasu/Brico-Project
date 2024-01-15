<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
    ){}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('Admin');
        $admin->setLastname('Sayedi');
        $admin->setFirstname('Donia');
        $admin->setEmail('admin@root.com');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setPhoneNumber('012555888');
        $admin->setNumStreet('78 rue des Dragons');
        $admin->setCity('Anderlecht');
        $admin->setZipcode('1070');
        $admin->setIsVerified(true);

        $manager->persist($admin);

    }
}