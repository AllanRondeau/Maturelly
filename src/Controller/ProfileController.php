<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Profile;
use App\Entity\ProfileImage;
use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id?}', name: 'app_profile')]
    public function profile(?User $user, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();
        $chatId = '';

        if (null === $user) {
            if (!$currentUser) {
                return $this->redirectToRoute('app_login');
            }

            $profile = $entityManager->getRepository(Profile::class)->findOneBy(['user' => $currentUser]);

            if (!$profile) {
                return $this->redirectToRoute('app_profile_edit');
            }
        } else {
            $profile = $entityManager->getRepository(Profile::class)->findOneBy(['user' => $user]);

            if (!$profile) {
                return $this->redirectToRoute('app_home');
            }

            $chat = $entityManager->getRepository(Chat::class)->findOneBy(['user1' => $user, 'user2' => $currentUser]);
            null === $chat ? $chat = $entityManager->getRepository(Chat::class)->findOneBy(['user2' => $user, 'user1' => $currentUser]) : null;
            null !== $chat ? $chatId = $chat->getId() : null;
        }

        return $this->render('profile/profile.html.twig', [
            'profile' => $profile,
            'IsCurrentUserProfile' => $profile->getUser() === $currentUser,
            'chatId' => $chatId,
        ]);
    }

    #[Route('/profile-edit', name: 'app_profile_edit')]
    public function profileCreation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $profile = $entityManager->getRepository(Profile::class)->findOneBy(['user' => $user]);
        $boolNewUser = false;

        if (!$profile) {
            $boolNewUser = true;
            $profile = new Profile();
            $profile->setUser($user);
        }

        $form = $this->createForm(ProfileFormType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $existingImagesCount = count($profile->getImages());
            $newImagesCount = count($images);
            $maxNbImages = 5;

            $nbImageCheck = true;
            if ($existingImagesCount + $newImagesCount > $maxNbImages) {
                $this->addFlash('error', 'Vous ne pouvez pas uploader plus de 5 images hors photo de profil.');
                $nbImageCheck = false;
            }

            if ($nbImageCheck) {
                if ($images) {
                    foreach ($images as $imageFile) {
                        if ($imageFile instanceof UploadedFile) {
                            $newFilename = uniqid('', true).'.'.$imageFile->guessExtension();

                            $imageFile->move(
                                $this->getParameter('profile_images_directory'),
                                $newFilename
                            );

                            $profileImage = new ProfileImage();
                            $profileImage->setFileName($newFilename);
                            $profileImage->setProfile($profile);

                            $entityManager->persist($profileImage);
                        }
                    }
                }

                $profilePictureFile = $form->get('profilePicture')->getData();
                if ($profilePictureFile instanceof UploadedFile) {
                    $oldProfilePicture = $profile->getProfilePicture();
                    $newProfilePictureFilename = uniqid('', true).'.'.$profilePictureFile->guessExtension();

                    if ($oldProfilePicture) {
                        $oldFilePath = $this->getParameter('profile_images_directory').'/'.$oldProfilePicture;
                        if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    $profilePictureFile->move(
                        $this->getParameter('profile_images_directory'),
                        $newProfilePictureFilename
                    );

                    $profile->setProfilePicture($newProfilePictureFilename);
                }

                $entityManager->persist($profile);
                $entityManager->flush();

                if ($boolNewUser) {
                    return $this->redirectToRoute('app_home');
                } else {
                    return $this->redirectToRoute('app_profile');
                }
            }
        }

        return $this->render('profile/form.html.twig', [
            'profileForm' => $form->createView(),
            'isNewUser' => $boolNewUser,
            'profile' => $profile,
        ]);
    }

    #[Route('/profile/image/delete/{id}', name: 'profile_image_delete', methods: ['DELETE'])]
    public function deleteImage($id, EntityManagerInterface $entityManager)
    {
        $profileImage = $entityManager->getRepository(ProfileImage::class)->find($id);

        if (!$profileImage) {
            throw $this->createNotFoundException('Image not found.');
        }

        $filePath = $this->getParameter('profile_images_directory').'/'.$profileImage->getFileName();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $entityManager->remove($profileImage);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile_edit');
    }
}
