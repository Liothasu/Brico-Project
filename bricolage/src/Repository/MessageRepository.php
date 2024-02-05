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
     * Find a message by its ID.
     *
     * @param int $id The ID of the message to find.
     *
     * @return Message|null The message corresponding to the given ID, or null if no message is found.
     */
    public function findMessageById(int $id): ?Message
    {
        return $this->find($id);
    }

    /**
     * Retrieves the sender of a message by its ID
     *
     * @param int $messageId Message ID
     * @return \App\Entity\User|null Message sender or null if not found
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

    public function countUnreadMessagesForUser($user)
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.recipient = :user')
            ->andWhere('m.is_read = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
