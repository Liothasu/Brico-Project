<?php

namespace App\Controller\Admin;

use App\Entity\Promo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class PromoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Promo::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');

        yield NumberField::new('percent')
            ->setLabel('Percentage');

        yield DateTimeField::new('dateBegin')
            ->setLabel('Start Date')
            ->setFormat('dd-MM-yyyy | HH:mm');

        yield DateTimeField::new('dateEnd')
            ->setLabel('End Date')
            ->setFormat('dd-MM-yyyy | HH:mm');

        yield AssociationField::new('products')
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,
        ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['dateBegin' => 'ASC'])
            ->setPaginatorPageSize(20);
    }
}
