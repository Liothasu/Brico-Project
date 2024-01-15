<?php

namespace App\Repository;

use App\Entity\Promo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promo>
 *
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

    /**
     * Retourne les promotions actives à la date actuelle.
     *
     * @return array|Promo[]
     */
    public function findActivePromos()
{
    $now = new \DateTimeImmutable();

    return $this->createQueryBuilder('p')
        ->andWhere('p.dateBegin <= :now')
        ->andWhere('p.dateEnd >= :now')
        ->setParameter('now', $now)
        ->orderBy('p.dateBegin', 'ASC')
        ->getQuery()
        ->getResult();
}
}

