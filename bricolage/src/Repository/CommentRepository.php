<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findForPagination(?Blog $blog = null): Query
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC');

        if ($blog) {
            $qb
                ->leftJoin('b.blog', 'blog')
                ->leftJoin('b.answers', 'answers')
                ->where($qb->expr()->eq('blog.id', ':blogId'))
                ->setParameter('blogId', $blog->getId());
        }

        return $qb->getQuery();
    }
}
