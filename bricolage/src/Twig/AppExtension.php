<?php

namespace App\Twig;

use App\Controller\Admin\BlogCrudController;
use App\Controller\Admin\TypeCrudController;
use App\Entity\Blog;
use App\Entity\Type;
use App\Entity\Comment;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Bundle\SecurityBundle\Security;

class AppExtension extends AbstractExtension
{
    const ADMIN_NAMESPACE = 'App\Controller\Admin';

    public function __construct(
        private RouterInterface $router,
        private AdminUrlGenerator $adminUrlGenerator,
        private Security $security,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ea_admin_url', [$this, 'getAdminUrl']),
            new TwigFunction('ea_edit', [$this, 'getAdminEditUrl']),
            new TwigFunction('entity_label', [$this, 'getEditCurrentEntityLabel']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('menuLink', [$this, 'menuLink']),
            new TwigFilter('typesToString', [$this, 'typesToString']),
            new TwigFilter('isCommentAuthor', $this->isCommentAuthor(...)),
        ];
    }

    public function typesToString(Collection $types): string
    {
        $generateTypeLink = function(Type $type) {
            $url = $this->router->generate('type_show', [
                'slug' => $type->getSlug()
            ]);
            return "<a href='$url' class='text-decoration-none' style='color: {$type->getColor()}'>{$type->getName()}</a>";
        };

        $typeLinks = array_map($generateTypeLink, $types->toArray());

        return implode(', ', $typeLinks);
    }

    public function getEditCurrentEntityLabel(object $entity): string
    {
        return match($entity::class) {
            Blog::class => "Edit blog",
            Type::class => 'Edit type',
        };
    }

    public function getAdminUrl(string $controller, string $action = Action::INDEX): string
    {
        return $this->adminUrlGenerator
            ->setController(self::ADMIN_NAMESPACE . '\\' . $controller)
            ->setAction($action)
            ->generateUrl();
    }

    public function getAdminEditUrl(object $entity): ?string
    {
        $crudController = match ($entity::class) {
            Blog::class => BlogCrudController::class,
            Type::class => TypeCrudController::class,
        };

        return $this->adminUrlGenerator
            ->setController($crudController)
            ->setAction(Action::EDIT)
            ->setEntityId($entity->getId())
            ->generateUrl();
    }

    public function isCommentAuthor(Comment $comment): bool
    {
        return $this->security->getUser()?->getUserIdentifier() === $comment->getUser()?->getUserIdentifier();
    }
}