<?php

namespace App\Repository;

use App\Entity\Dispute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dispute>
 *
 * @method Dispute|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dispute|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dispute[]    findAll()
 * @method Dispute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisputeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dispute::class);
    }
}
