<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Model\FilterData;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
        $data = $this->createQueryBuilder('p');

        if (!empty($searchData->q)) {
            $data = $data
                ->join('p.category', 'c')
                ->andWhere('(p.nameProduct LIKE :q OR c.name LIKE :q)')
                ->setParameter('q', "%{$searchData->q}%");
        }

        $data = $data
            ->getQuery()
            ->getResult();

        $products = $this->paginatorInterface->paginate($data, $searchData->page, 9);

        return $products;
    }

    public function findByFilter(FilterData $filterData): PaginationInterface
    {
        $data = $this->createQueryBuilder('p');

        if (!empty($filterData->categories)) {
            $data = $data
                ->join('p.category', 'cat')
                ->andWhere('cat.id IN (:categories)')
                ->orderBy('p.nameProduct', 'ASC')
                ->setParameter('categories', $filterData->categories);
        }

        $data = $data
            ->getQuery()
            ->getResult();

        $products = $this->paginatorInterface->paginate($data, $filterData->page, 9);

        return $products;
    }

    // public function findForPagination(?Category $category = null): Query
    // {
    //     $qb = $this->createQueryBuilder('p')
    //         ->orderBy('p.nameProduct', 'ASC');

    //     if ($category) {
    //         $qb->leftJoin('p.categories', 'c')
    //             ->where($qb->expr()->eq('c.id', ':id'))
    //             ->setParameter('id', $category->getId());
    //     }

    //     return $qb->getQuery();
    // }
}
