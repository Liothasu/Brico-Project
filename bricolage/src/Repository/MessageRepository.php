<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Trouve un message par son ID.
     *
     * @param int $id L'ID du message à trouver.
     *
     * @return Message|null Le message correspondant à l'ID donné, ou null si aucun message n'est trouvé.
     */
    public function findMessageById(int $id): ?Message
    {
        return $this->find($id);
    }

    /**
     * Récupère l'émetteur d'un message par son ID
     *
     * @param int $messageId L'ID du message
     * @return \App\Entity\User|null L'émetteur du message ou null si non trouvé
     */
    public function findSenderByMessageId(int $messageId): ?\App\Entity\User
    {
        return $this->createQueryBuilder('m')
            ->select('u')
            ->join('m.user', 'u')
            ->where('m.id = :messageId')
            ->setParameter('messageId', $messageId)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
