<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CommentService
{
    public function __construct(
        private BlogRepository $blogRepository,
        private CommentRepository $commentRepository,
        private EntityManagerInterface $em,
        private NormalizerInterface $normalizer,
        private PaginatorInterface $paginator,
        private RequestStack $requestStack,
        private TokenStorageInterface $tokenStorage,
        private Security $security
    ) {
    }

    public function getPaginatedComments(?Blog $blog = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 3;

        $commentsQuery = $this->commentRepository->findForPagination($blog);

        return $this->paginator->paginate($commentsQuery, $page, $limit);
    }


    public function add(array $data, Blog $blog, ?Comment $parent = null, bool $isAnswer = false): ?Comment
    {
        $comment = new Comment($blog, $this->security->getUser());
        $comment->setContent($data['content']);
        $comment->setCreatedAt(new \DateTime());

        if ($isAnswer) {
            $comment->setParent($parent);
        }

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

    public function deletePreliminaryChecks(?Comment $comment): ?JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([
                'code' => 'NOT_AUTHENTICATED'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$comment) {
            return new JsonResponse([
                'code' => 'COMMENT_NOT_FOUND'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($this->security->getUser() !== $comment->getUser()) {
            return new JsonResponse([
                'code' => 'UNAUTHORIZED'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return null;
    }

    public function edit(Comment $comment, string $content): void
    {
        $comment->setContent($content);
        $comment->setUpdatedAt(new \DateTime());

        $this->em->flush();
    }

    public function delete(Comment $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function normalize(Comment $comment): array
    {
        return $this->normalizer->normalize($comment, context: [
            'groups' => 'comment'
        ]);
    }
}
