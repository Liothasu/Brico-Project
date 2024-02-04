<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function findForPagination(?Type $type = null): Query
    {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC');

        if ($type) {
            $qb->leftJoin('b.types', 't')
                ->where($qb->expr()->eq('t.id', ':id'))
                ->setParameter('id', $type->getId());
        }

        return $qb->getQuery();
    }

    /**
     * Get the most recent blogs
     *
     * @param int $limit Maximum number of blogs to retrieve
     * @return Blog[]
     */
    public function findRecentBlogs($limit = 5)
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
