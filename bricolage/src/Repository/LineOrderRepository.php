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
}
