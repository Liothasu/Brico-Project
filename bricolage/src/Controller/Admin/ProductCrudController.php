<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference'),
            TextField::new('nameProduct'),
            TextField::new('color'),
            TextField::new('designation'),
            IntegerField::new('quantity'),
            IntegerField::new('unitPrice'),
            IntegerField::new('priceVAT'),
            IntegerField::new('vat'),
            SlugField::new('slug')->setTargetFieldName('nameProduct'),
        ];
    }
}
