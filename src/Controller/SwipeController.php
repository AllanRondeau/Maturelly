<?php 

namespace App\Controller;

use App\Entity\User;
use App\Service\MatchService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/swipe')]
class SwipeController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private MatchService $matchService,
        private Security $security
    ) {}

    #[Route('', name: 'app_swipe')]
    public function index(): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }
        // La vue chargera les profils via AJAX
        return $this->render('swipe/index.html.twig');
    }

    #[Route('/all-profiles', name: 'app_swipe_all_profiles')]
    public function getAllPotentialMatches(): JsonResponse
    {
        $currentUser = $this->getUser();
        $profiles = $this->userRepository->findAllPotentialMatches($currentUser);
        
        return $this->json([
            'profiles' => array_map(fn($user) => [
                'id' => $user->getId(),
                'name' => explode('@', $user->getEmail())[0],
                'gender' => $user->getGender(),
                'location' => 'Paris, France',
            ], $profiles)
        ]);
    }

    #[Route('/like/{id}', name: 'app_swipe_like', methods: ['POST'])]
    public function like(User $targetUser): JsonResponse
{
    $result = $this->matchService->handleLike($this->getUser(), $targetUser);
    return $this->json([
        'isMatch' => $result['isMatch'] ?? false,
        'matchedUser' => $result['isMatch'] ? [
            'name' => explode('@', $targetUser->getEmail())[0],
            'id' => $targetUser->getId()
        ] : null
    ]);
}

    #[Route('/dislike/{id}', name: 'app_swipe_dislike', methods: ['POST'])]
    public function dislike(User $targetUser): JsonResponse
    {
        $this->matchService->handleDislike($this->getUser(), $targetUser);
        return $this->json(['status' => 'success']);
    }
}
