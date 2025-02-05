<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function createLikeNotification(User $liked, User $liker): void
    {
        $notification = new Notification();
        $notification->setUser($liked)
            ->setType('like')
            ->setMessage(sprintf("%s vous a liké !", $liker->getEmail()))
            ->setIsRead(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    public function createMatchNotification(User $user1, User $user2): void
    {
        // Notification pour user1
        $notification1 = new Notification();
        $notification1->setUser($user1)
            ->setType('match')
            ->setMessage(sprintf("Vous avez matché avec %s !", $user2->getEmail()))
            ->setIsRead(false);

        // Notification pour user2
        $notification2 = new Notification();
        $notification2->setUser($user2)
            ->setType('match')
            ->setMessage(sprintf("Vous avez matché avec %s !", $user1->getEmail()))
            ->setIsRead(false);

        $this->entityManager->persist($notification1);
        $this->entityManager->persist($notification2);
        $this->entityManager->flush();
    }
}