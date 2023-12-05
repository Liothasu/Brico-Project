<?php

namespace App\Repository;

use App\Entity\LineOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LineOrder>
 *
 * @method LineOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineOrder[]    findAll()
 * @method LineOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineOrder::class);
    }

//    /**
//     * @return LineOrder[] Returns an array of LineOrder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LineOrder
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
