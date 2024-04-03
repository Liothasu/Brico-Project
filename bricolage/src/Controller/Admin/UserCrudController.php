<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username');

        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                'ROLE_HANDYMAN' => 'warning',
                'ROLE_USER' => 'danger'
            ])
            ->setChoices([
                'Administrator' => 'ROLE_ADMIN',
                'Handyman' => 'ROLE_HANDYMAN',
                'Customer' => 'ROLE_USER',
            ]);

        yield TextField::new('lastName');

        yield TextField::new('firstName');

        yield TextField::new('email');

        yield IntegerField::new('phoneNumber');

        yield TextField::new('numStreet');

        yield TextField::new('city');

        yield IntegerField::new('zipCode');
    }
}
