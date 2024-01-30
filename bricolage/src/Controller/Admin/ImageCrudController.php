<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ProductType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $mediasDir = $this->getParameter('medias_directory');
        $imgDir = $this->getParameter('uploads_directory');

        yield AssociationField::new('product');

        yield TextField::new('name');

        yield ImageField::new('filename', 'Image')
            ->setBasePath($imgDir)
            ->setUploadDir($mediasDir)
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]');
    }
}
