<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        //Admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@hardware-store.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);

        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setPhoneNumber('123456789');
        $admin->setNumStreet('123 Main St');
        $admin->setCity('City');
        $admin->setZipCode(12345);
        $admin->setIsVerified(true);
        $manager->persist($admin);

        //Handyman
        $handyman = new User();
        $handyman->setUsername('handyman');
        $handyman->setEmail('handyman@hardware-store.com');
        $handyman->setRoles(['ROLE_HANDYMAN']);

        $hashedPassword = $this->passwordHasher->hashPassword($handyman, 'handyman');
        $handyman->setPassword($hashedPassword);

        $handyman->setFirstName('Handyman');
        $handyman->setLastName('User');
        $handyman->setPhoneNumber('123456789');
        $handyman->setNumStreet('123 Main St');
        $handyman->setCity('City');
        $handyman->setZipCode(12345);
        $handyman->setIsVerified(true);
        $manager->persist($handyman);

        //User
        $user = new User();
        $user->setUsername('Liothasu');
        $user->setEmail('liothasu@hardware-store.com');
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, '1234567');
        $user->setPassword($hashedPassword);

        $user->setFirstName('Donia');
        $user->setLastName('Sayedi');
        $user->setPhoneNumber('0477584566');
        $user->setNumStreet('Rue du Centre 4');
        $user->setCity('Masbourg');
        $user->setZipCode(6953);
        $user->setIsVerified(true);
        $manager->persist($user);

        $manager->flush();
    }
}