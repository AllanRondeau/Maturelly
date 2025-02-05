<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findMatch(User $user1, User $user2): ?Like
    {
        $parameters = new ArrayCollection();
        $parameters->add(['key' => 'user1', 'value' => $user1]);
        $parameters->add(['key' => 'user2', 'value' => $user2]);
        $parameters->add(['key' => 'isLike', 'value' => true]);

        $queryBuilder = $this->createQueryBuilder('l')
            ->where('l.liker = :user2')
            ->andWhere('l.liked = :user1')
            ->andWhere('l.isLike = :isLike');

        foreach ($parameters as $param) {
            $queryBuilder->setParameter($param['key'], $param['value']);
        }

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
    }
}