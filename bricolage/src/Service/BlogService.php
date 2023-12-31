<?php

namespace App\Service;

use App\Entity\Type;
use App\Entity\Config;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BlogService
{
    public function __construct(
        private RequestStack $requestStack,
        private BlogRepository $blogRepository,
        private ConfigService $configService,
        private PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedBlogs(?Type $type = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $blogsQuery = $this->blogRepository->findForPagination($type);
        $page = $request->query->getInt('page', 1);
        $limit = $this->configService->getValue(Config::BLOG_LIMIT);

        return $this->paginator->paginate($blogsQuery, $page, $limit);
    }

    public function findBlogById(?int $id): ?Blog
    {
        if ($id === null) {
            return null;
        }

        return $this->blogRepository->find($id);
    }
}
