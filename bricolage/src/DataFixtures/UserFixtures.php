<?php

namespace App\DataFixtures;

use App\Entity\Admin;
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
        $admin = new Admin();
        $admin->setUsername('Admin');
        $admin->setEmail('admin@hardware-store.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);

        $hashedPassword = '$2y$13$a7WxmqKDw4oj/XHZpIUsi.zWKZFXZYdG0EfG2n7lJDw44/UiZH5Tq';
        $admin->setPassword($hashedPassword);

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