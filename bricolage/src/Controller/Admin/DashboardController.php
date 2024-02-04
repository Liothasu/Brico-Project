<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Type;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Config;
use App\Entity\Dispute;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\Promo;
use App\Entity\Supplier;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) 
    {

    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_HANDYMAN')) {
            return new Response($this->renderView('pages/security/access_denied.html.twig'), Response::HTTP_FORBIDDEN);
        }

        $url = $this->adminUrlGenerator
            ->setController(BlogCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hardware-Store | Admin')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Go to the website', 'fas fa-undo', 'home');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Products', 'fa-solid fa-tags')->setSubItems([
                MenuItem::linkToCrud('All Products', 'fa-solid fa-tag', Product::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Product::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Categories', 'fa-solid fa-lines-leaning', Category::class),
                MenuItem::linkToCrud('Suppliers', 'fa-solid fa-industry', Supplier::class)
            ]);

            yield MenuItem::subMenu('Promos', 'fa-solid fa-percent')->setSubItems([
                MenuItem::linkToCrud('All promos', 'fa-solid fa-percent', Promo::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Promo::class)->setAction(Crud::PAGE_NEW),
            ]);

            yield MenuItem::linkToCrud('Orders', 'fa-solid fa-cart-arrow-down', Order::class);
        }

        if ($this->isGranted('ROLE_HANDYMAN')) {
            yield MenuItem::subMenu('Blogs', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('All Blogs', 'fas fa-newspaper', Blog::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Blog::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Types', 'fas fa-list', Type::class)
            ]);

            yield MenuItem::subMenu('Projects', 'fa-solid fa-pen-ruler')->setSubItems([
                MenuItem::linkToCrud('All Projects', 'fa-solid fa-pen-ruler', Project::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Project::class)->setAction(Crud::PAGE_NEW),
            ]);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Media Library', 'fas fa-photo-video')->setSubItems([
                MenuItem::linkToCrud('Medias', 'fa-solid fa-image', Media::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Media::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Images', 'fa-solid fa-image', Image::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Image::class)->setAction(Crud::PAGE_NEW),
            ]);

            yield MenuItem::linkToCrud('Messages', 'fa-solid fa-envelope', Message::class);

            yield MenuItem::linkToCrud('Comments', 'fa-solid fa-comments', Comment::class);

            yield MenuItem::linkToCrud('Disputes', 'fa-solid fa-file-pen', Dispute::class);

            yield MenuItem::linkToCrud('Accounts', 'fas fa-user-friends', User::class);

            yield MenuItem::subMenu('Settings', 'fas fa-cog')->setSubItems([
                MenuItem::linkToCrud('General', 'fas fa-cog', Config::class),
            ]);
        }
    }
}