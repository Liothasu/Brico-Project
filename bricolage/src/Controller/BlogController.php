<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'list_blog')]
    public function listBlog(BlogRepository $blogRepository): Response
    {
        $blogs = $blogRepository->findAll();

        return $this->render('blog/list_blog.twig', [
            'blogs' => $blogs,
        ]);
            
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function showBlog(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    // #[Route('/blogs/new', name: 'blog_new')]
    // public function newBlog(Request $request): Response
    // {
    //     return void;
    // }
}
