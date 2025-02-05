<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Like;
use App\Entity\Chat;
use App\Repository\LikeRepository;
use App\Repository\ChatRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LikeRepository $likeRepository,
        private ChatRepository $chatRepository
    ) {}

    public function handleLike(User $liker, User $liked): array
    {
        $like = new Like();
        $like->setLiker($liker)
             ->setLiked($liked)
             ->setIsLike(true);

        $this->entityManager->persist($like);
        
        // Vérifie s'il y a un match
        $isMatch = $this->likeRepository->findMatch($liker, $liked) !== null;
        
        if ($isMatch) {
            // Vérifie si un chat existe déjà entre ces utilisateurs
            $existingChat = $this->chatRepository->findChatBetweenUsers($liker, $liked);
            
            if (!$existingChat) {
                // Crée un nouveau chat
                $chat = new Chat();
                $chat->setUser1($liker);
                $chat->setUser2($liked);
                
                $this->entityManager->persist($chat);
            }
        }

        $this->entityManager->flush();

        return [
            'isMatch' => $isMatch,
            'chatId' => $isMatch ? ($existingChat ?? $chat)->getId() : null
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