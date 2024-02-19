<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository,
        private PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedProduts(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $productsQuery = $this->productRepository->findForPagination($category);
        $page = $request->query->getInt('page', 1);
        $limit = 4;

        return $this->paginator->paginate($productsQuery, $page, $limit);
    }

    public function findProductsById(?int $id): ?Product
    {
        if ($id === null) {
            return null;
        }

        return $this->productRepository->find($id);
    }
}
