<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findUnreadByUser(User $user): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :user')
            ->andWhere('n.isRead = :isRead')
            ->setParameter('user', $user)
            ->setParameter('isRead', false)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentByUser(User $user, int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :user')
            ->setParameter('user', $user)
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function markAllAsRead(User $user): void
    {
        $qb = $this->createQueryBuilder('n');
        
        $qb->update()
           ->set('n.isRead', true)
           ->where($qb->expr()->eq('n.user', ':user'))
           ->andWhere($qb->expr()->eq('n.isRead', ':oldStatus'))
           ->setParameter('user', $user)
           ->setParameter('oldStatus', false)
           ->getQuery()
           ->execute();
    }

    public function deleteOldNotifications(User $user, \DateTime $before): void
    {
        $qb = $this->createQueryBuilder('n');
        
        $qb->delete()
           ->where($qb->expr()->eq('n.user', ':user'))
           ->andWhere($qb->expr()->lt('n.createdAt', ':before'))
           ->setParameter('user', $user)
           ->setParameter('before', $before)
           ->getQuery()
           ->execute();
    }

    public function countUnreadByUser(User $user): int
    {
        return (int) $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->andWhere('n.user = :user')
            ->andWhere('n.isRead = :isRead')
            ->setParameter('user', $user)
            ->setParameter('isRead', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
}