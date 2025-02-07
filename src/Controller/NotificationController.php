<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NotificationRepository $notificationRepository,
    ) {
    }

    #[Route('/notifications', name: 'app_notifications_list')]
    public function list(): JsonResponse
    {
        $notifications = $this->notificationRepository->findBy(
            ['user' => $this->getUser(), 'isRead' => false],
            ['createdAt' => 'DESC']
        );

        return $this->json([
            'notifications' => array_map(function ($notification) {
                return [
                    'id' => $notification->getId(),
                    'type' => $notification->getType(),
                    'message' => $notification->getMessage(),
                    'createdAt' => $notification->getCreatedAt()->format('c'),
                ];
            }, $notifications),
        ]);
    }

    #[Route('/notifications/{id}/read', name: 'app_notifications_read', methods: ['POST'])]
    public function markAsRead(string $id): JsonResponse
    {
        $notification = $this->notificationRepository->find($id);

        if (!$notification || $notification->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Notification not found'], 404);
        }

        $notification->setIsRead(true);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }
}
