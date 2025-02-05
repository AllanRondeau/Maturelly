<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LikeRepository $likeRepository
    ) {}

    public function handleLike(User $liker, User $liked): array
    {
        $like = new Like();
        $like->setLiker($liker)
             ->setLiked($liked)
             ->setIsLike(true);

        $this->entityManager->persist($like);
        $this->entityManager->flush();

        $isMatch = $this->likeRepository->findMatch($liker, $liked) !== null;

        return ['isMatch' => $isMatch];
    }

    public function handleDislike(User $liker, User $liked): void
    {
        $like = new Like();
        $like->setLiker($liker)
             ->setLiked($liked)
             ->setIsLike(false);

        $this->entityManager->persist($like);
        $this->entityManager->flush();
    }
}