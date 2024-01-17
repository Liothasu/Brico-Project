<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findByUserWithStatus($userId)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->leftJoin('o.status', 's')
            ->getQuery()
            ->getResult();
    }

    public function findCartByUser($user)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :user')
            ->andWhere('o.statutOrders IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('statuses', ['ORDER_IN_PROCESS', 'ORDER_PAID', 'ORDER_CANCELED'])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Order[]
     */
    public function findAllOrders(): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.reference', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
