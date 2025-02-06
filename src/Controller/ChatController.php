<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

final class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos chats.');
        }

        $chats = $entityManager->getRepository(Chat::class)->createQueryBuilder('c')
            ->where('c.user1 = :user')
            ->orWhere('c.user2 = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'chats' => $chats,
        ]);
    }

    #[Route('/chat/{id}', name: 'app_chat_show')]
    public function show(
        string $id,
        EntityManagerInterface $entityManager,
        Security $security,
        Request $request,
        MessageRepository $messageRepository,
        HubInterface $hub,
    ): Response {
        /** @var User $user */
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos chats.');
        }

        $chat = $entityManager->getRepository(Chat::class)->find($id);
        if (!$chat) {
            throw $this->createNotFoundException('Le chat demandé n\'existe pas.');
        }

        $messages = $messageRepository->findByChatOrdered($chat);

        $message = new Message();
        $message->setSender($user);
        $message->setChat($chat);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTime());
            $entityManager->persist($message);
            $entityManager->flush();

            $messageData = [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'sender' => [
                    /* @var User $user */
                    'id' => $user->getId()->toString(),
                    /* @var User $user */
                    'email' => $user->getEmail(),
                ],
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            ];

            try {
                $update = new Update(
                    sprintf('chat/%s', $chat->getId()),
                    json_encode($messageData),
                );

                $hub->publish($update);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la publication: '.$e->getMessage());
            }

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'success' => true,
                    'message' => $messageData,
                ]);
            }

            return $this->redirectToRoute('app_chat_show', ['id' => $chat->getId()]);
        }

        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
            'messages' => $messages,
            'mercureUrl' => $this->getParameter('mercure.default_hub'),
        ]);
    }
}
