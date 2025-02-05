<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAllPotentialMatches(User $user): array
{
    $qb = $this->createQueryBuilder('u')
        ->where('u.id != :currentUser')
        ->andWhere('u.gender != :userGender')
        ->setParameter('currentUser', $user)
        ->setParameter('userGender', $user->getGender());
        // je souhaite que l'admin ne figure pas dans les profils
        if ($user->getRoles() === ['ROLE_ADMIN']) {
            $qb->andWhere('u.roles != :adminRole')
                ->setParameter('adminRole', ['ROLE_ADMIN']);
        }

    $subQuery = $this->getEntityManager()
        ->createQueryBuilder()
        ->select('IDENTITY(l.liked)')
        ->from('App\Entity\Like', 'l')
        ->where('l.liker = :currentUser')
        ->getDQL();

    return $qb
        ->andWhere($qb->expr()->notIn('u.id', '(' . $subQuery . ')'))
        ->setParameter('currentUser', $user)
        ->getQuery()
        ->getResult();
}
    

    public function findNearbyUsers(User $user, float $maxDistance = 50): array
    {
        $lat = $user->getLatitude();
        $lon = $user->getLongitude();
        
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(User::class, 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'email', 'email');
        $rsm->addFieldResult('u', 'latitude', 'latitude');
        $rsm->addFieldResult('u', 'longitude', 'longitude');
        
        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT u.*, 
                (6371 * acos(cos(radians(:lat)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(:lon)) + 
                sin(radians(:lat)) * sin(radians(latitude)))) AS distance
            FROM "user" u
            WHERE u.id != :userId
            AND u.gender != :gender
            HAVING distance < :maxDistance
            ORDER BY distance',
            $rsm
        );

        $query->setParameters([
            'lat' => $lat,
            'lon' => $lon,
            'userId' => $user->getId(),
            'gender' => $user->getGender(),
            'maxDistance' => $maxDistance
        ]);

        return $query->getResult();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
