<?php

namespace App\Controller;

use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) {
            $profile = $entityManager->getRepository(Profile::class)->findOneBy(['user' => $user]);
            if (!$profile) {
                return $this->redirectToRoute('app_profile_edit');
            }
        }

        return $this->render('home/index.html.twig');
    }
}
