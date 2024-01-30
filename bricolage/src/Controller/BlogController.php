<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Media;
use App\Form\BlogEditType;
use App\Form\BlogType;
use App\Form\Type\CommentType;
use App\Repository\TypeRepository;
use App\Service\BlogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @method User getUser()
 */
class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_list')]
    public function listBlog(BlogService $blogService, TypeRepository $typeRepository): Response
    {
        $blogs = $blogService->getPaginatedBlogs();
    
        return $this->render('pages/blog/show.html.twig', [
            'blogs' => $blogs,
            'types' => $typeRepository->findAllForWidget()
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_show')]
    public function show(?Blog $blog, Request $request): Response
    {
        if (!$blog) {
            return $this->redirectToRoute('blog_list');
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

    #[Route('/new', name: 'blog_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('featuredMedia')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );

                $media = new Media();
                $media->setName($originalFilename);
                $media->setFilename($newFilename);

                $entityManager->persist($media);
                $entityManager->flush();

                $blog->setFeaturedMedia($media);
            }

            $blog->setSlug($slugger->slug($blog->getTitle())->toString());
            $blog->setAuthor($this->getUser());

            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_show', ['slug' => $blog->getSlug()]);
        }

        $referer = $request->headers->get('referer');

        return $this->render('pages/blog/new.html.twig', [
            'form' => $form->createView(),
            'referer' => $referer,
        ]);
    }

    #[Route('/blog/edit/{id}', name: 'blog_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Blog $blog): Response
    {
        $form = $this->createForm(BlogEditType::class, $blog);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('blog_show', ['slug' => $blog->getSlug()]);
        }

        $referer = $request->headers->get('referer');

        return $this->render('pages/blog/edit.html.twig', [
            'form' => $form->createView(),
            'referer' => $referer,
            'blog' => $blog,
        ]);
    }

    #[Route('blog/delete/{id}', name: 'blog_delete')]
    public function deleteProject(Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user !== $blog->getAuthor()) {
           return $this->redirectToRoute('blog_show', ['slug' => $blog->getSlug()]);
        }

        $entityManager->remove($blog);
        $entityManager->flush();

        $this->addFlash('message', 'Your blog has been cancelled');
       return $this->redirectToRoute('blog_show', ['slug' => $blog->getSlug()]);
    }
}
