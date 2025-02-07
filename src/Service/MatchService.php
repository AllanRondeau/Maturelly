<?php

namespace App\Service;

use App\Entity\Chat;
use App\Entity\Like;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LikeRepository $likeRepository,
        private ChatRepository $chatRepository,
    ) {
    }

    public function handleLike(User $liker, User $liked): array
    {
        $like = new Like();
        $like->setLiker($liker)
             ->setLiked($liked)
             ->setIsLike(true);

        $this->entityManager->persist($like);

        $isMatch = null !== $this->likeRepository->findMatch($liker, $liked);
        $chat = new Chat();

        if ($isMatch) {
            $existingChat = $this->chatRepository->findChatBetweenUsers($liker, $liked);

            if (!$existingChat) {
                $chat->setUser1($liker);
                $chat->setUser2($liked);

                $this->entityManager->persist($chat);
            }
        }

        $this->entityManager->flush();

        return [
            'isMatch' => $isMatch,
            'chatId' => $isMatch ? ($existingChat ?? $chat)->getId() : null,
        ];
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
