<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Model\FilterData;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllOrderedByName()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nameProduct', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllCategories()
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }

    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if (!empty($searchData->q)) {
            $queryBuilder
                ->join('p.category', 'c')
                ->andWhere('(p.nameProduct LIKE :q OR c.name LIKE :q)')
                ->setParameter('q', "%{$searchData->q}%");
        }

        return $this->paginatorInterface->paginate($queryBuilder->getQuery(), $searchData->page, 10);
    }

    public function findByFilter(FilterData $filterData): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if (!empty($filterData->categories)) {
            $queryBuilder
                ->join('p.category', 'cat')
                ->andWhere('cat.id IN (:categories)')
                ->orderBy('p.nameProduct', 'ASC')
                ->setParameter('categories', $filterData->categories);
        }

        return $this->paginatorInterface->paginate($queryBuilder->getQuery(), $filterData->page, 3);
    }

    public function findForPagination(?Category $category = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->orderBy('p.nameProduct', 'ASC');

        if ($category) {
            $queryBuilder
                ->leftJoin('p.categories', 'c')
                ->where($queryBuilder->expr()->eq('c.id', ':id'))
                ->setParameter('id', $category->getId());
        }

        return $queryBuilder;
    }
}
