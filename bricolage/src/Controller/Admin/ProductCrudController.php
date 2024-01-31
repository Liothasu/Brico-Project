<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\HttpFoundation\Response;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewProduct = Action::new('viewProduct', "View the product")
            ->setHtmlAttributes([
                'target' => '_blank'
            ])
            ->linkToCrudAction('viewProduct');

        return $actions
            ->add(Crud::PAGE_EDIT, $viewProduct)
            ->add(Crud::PAGE_INDEX, $viewProduct);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('images');

        yield TextField::new('nameProduct');

        yield SlugField::new('slug')
            ->setTargetFieldName('nameProduct');

        yield AssociationField::new('category');

        yield AssociationField::new('supplier');

        yield TextField::new('reference');

        yield TextField::new('color');

        yield TextField::new('designation');

        yield IntegerField::new('quantity');

        yield NumberField::new('priceVAT')
            ->setLabel('Prix TTC')
            ->setNumDecimals(2);

        yield NumberField::new('unitPrice')
            ->setLabel('Prix unitaire HT')
            ->setNumDecimals(2)
            ->hideOnForm();

        yield AssociationField::new('promos', 'Promos')
            ->onlyOnIndex();
    }

    public function viewProduct(AdminContext $context): Response
    {
        /** @var Product $product */
        $product = $context->getEntity()->getInstance();

        $category = $product->getCategory();

        if ($category) {
            return $this->redirectToRoute('category_list', [
                'slug' => $category->getSlug()
            ]);
        }

        return $this->redirectToRoute('home');
    }
}