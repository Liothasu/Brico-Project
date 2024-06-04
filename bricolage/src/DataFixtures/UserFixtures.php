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
        $admin->setUsername('Admin');
        $admin->setEmail('admin@brico-project.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $hashedPassword = '$2y$13$a7WxmqKDw4oj/XHZpIUsi.zWKZFXZYdG0EfG2n7lJDw44/UiZH5Tq';
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
        $handyman->setEmail('handyman@brico-project.com');
        $handyman->setRoles(['ROLE_HANDYMAN']);

        $hashedPassword = '$2y$13$owPMsSo0XyLHC3UYqLxhR.8f0ZIiymzguREHIhdA.9/m1JM5LPOwy';
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
        $user->setEmail('liothasu@brico-project.com');
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, '12345678');
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