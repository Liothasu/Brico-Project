<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
         ->setPageTitle('index', "Hardware-Store/Admin");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            ArrayField::new('role'),
            TextField::new('username'),
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('email'),
            TextField::new('phoneNumber'),
            TextField::new('numStreet'),
            IntegerField::new('city'),
            IntegerField::new('zipCode'),
        ];
    }
}
