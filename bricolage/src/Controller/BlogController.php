<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\Type\CommentType;
use App\Repository\TypeRepository;
use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @method User getUser()
 */
class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_list')]
    public function listBlog(BlogService $blogService, TypeRepository $typeRepository): Response
    {
        $blogs = $blogService->getPaginatedBlogs();
    
        return $this->render('pages/blog/exemple.html.twig', [
            'blogs' => $blogs,
            'types' => $typeRepository->findAllForWidget()
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_show')]
    public function show(?Blog $blog, Request $request): Response
    {
        if (!$blog) {
            return $this->redirectToRoute('home');
        }

        $parameters = [
            'entity' => $blog,
            'preview' => $request->query->getBoolean('preview'),
        ];

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $commentForm = $this->createForm(CommentType::class, new Comment($blog, $this->getUser()));
            $parameters['commentForm'] = $commentForm;
        }

        return $this->render('pages/blog/index.html.twig', $parameters);
    }

    #[Route('/ajax/blogs/{id}/comments', name: 'blog_list_comments', methods: ['GET'])]
    public function listComments(?Blog $blog, NormalizerInterface $normalizer): Response
    {
        $comments = $normalizer->normalize($blog->getComments(), context: [
            'groups' => 'comment',
        ]);

        return $this->json($comments);
    }
}
