<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @method User getUser()
 */
class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $mediasDir = $this->getParameter('medias_directory');
        $uploadsDir = $this->getParameter('uploads_directory');

        yield TextField::new('name');

        yield $imageField = ImageField::new('filename', 'Image')
            ->setBasePath($uploadsDir)
            ->setUploadDir($mediasDir)
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]');

        if (Crud::PAGE_EDIT == $pageName) {
            $imageField->setRequired(false);
        }
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Media $media */
        $media = $entityInstance;

        $mediaName = $media->getName() ?: $media->getFilename();
        $media->setName($mediaName);

        parent::persistEntity($entityManager, $media);
    }
}
