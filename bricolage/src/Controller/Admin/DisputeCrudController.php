<?php

namespace App\Controller\Admin;

use App\Entity\Dispute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DisputeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dispute::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
            TextField::new('title'),
            TextEditorField::new('description'),
            TextField::new('problemType'),
            AssociationField::new('blog')->formatValue(function ($value) {
                return $value ?: '-';
            }),
            AssociationField::new('project')->formatValue(function ($value) {
                return $value ?: '-';
            }),
            AssociationField::new('comment')->formatValue(function ($value) {
                return $value ?: '-';
            }),
            AssociationField::new('order')->formatValue(function ($value) {
                return $value ?: '-';
            }),
        ];
    }
}
