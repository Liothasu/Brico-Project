<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\Type\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;

class BlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blog::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewBlog = Action::new('viewBlog', "View the blog")
            ->setHtmlAttributes([
                'target' => '_blank'
            ])
            ->linkToCrudAction('viewBlog');

        return $actions
            ->add(Crud::PAGE_EDIT, $viewBlog)
            ->add(Crud::PAGE_INDEX, $viewBlog);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityPermission('ROLE_HANDYMAN');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');

        yield SlugField::new('slug')
            ->setTargetFieldName('title');

        yield TextEditorField::new('content');

        yield TextareaField::new('featuredText', 'Texte mis en avant');

        yield AssociationField::new('types');

        yield AssociationField::new('featuredMedia', 'Image');

        yield CollectionField::new('comments')
            ->setEntryType(CommentType::class)
            ->allowAdd(false)
            ->allowDelete(false)
            ->onlyOnForms()
            ->hideWhenCreating();

        if ($pageName === Crud::PAGE_INDEX) {
            yield DateTimeField::new('createdAt')->hideOnForm();
            yield DateTimeField::new('updatedAt')->hideOnForm();
        }
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Blog $blog */
        $blog = $entityInstance;

        if (!$blog->getCreatedAt()) {
            $now = new \DateTimeInterface();
            $blog->setCreatedAt($now);
        }

        $blog->setAuthor($this->getUser());

        parent::persistEntity($entityManager, $blog);
    }

    public function viewBlog(AdminContext $context): Response
    {
        /** @var Blog $blog */
        $blog = $context->getEntity()->getInstance();

        return $this->redirectToRoute('blog_show', [
            'slug' => $blog->getSlug()
        ]);
    }
}