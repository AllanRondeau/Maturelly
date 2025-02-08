<?php

namespace App\DataFixtures;

use App\Entity\ProfileImage;
use App\Enum\Genders;
use App\Factory\HobbyFactory;
use App\Factory\ProfileFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = UserFactory::createOne([
            'email' => 'admin@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_ADMIN'],
            'gender' => Genders::MALE,
            'profile' => ProfileFactory::new(
                [
                    'hobbies' => HobbyFactory::new()->range(0, 10),
                    'profilePicture' => 'spaceman.webp',
                    'description' => "Je suis l'administrateur de Maturelly",
                ]
            ),
        ]);

        $image = new ProfileImage();
        $image->setFileName('amogusdrip.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $image = new ProfileImage();
        $image->setFileName('turtle.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $user = UserFactory::createOne([
            'email' => 'man@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'gender' => Genders::MALE,
            'profile' => ProfileFactory::new(
                [
                    'hobbies' => HobbyFactory::new()->range(0, 10),
                    'profilePicture' => 'fish.webp',
                ]
            ),
        ]);

        $image = new ProfileImage();
        $image->setFileName('man1.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $image = new ProfileImage();
        $image->setFileName('man2.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $image = new ProfileImage();
        $image->setFileName('man3.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $image = new ProfileImage();
        $image->setFileName('man4.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $image = new ProfileImage();
        $image->setFileName('landscape.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $user = UserFactory::createOne([
            'email' => 'woman@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'gender' => Genders::FEMALE,
            'profile' => ProfileFactory::new(
                [
                    'hobbies' => HobbyFactory::new()->range(0, 10),
                    'profilePicture' => 'woman1.webp',
                ]
            ),
        ]);

        $image = new ProfileImage();
        $image->setFileName('woman2.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $image = new ProfileImage();
        $image->setFileName('horsie.webp');
        $image->setProfile($user->getProfile());
        $manager->persist($image);

        $manager->flush();

        UserFactory::createMany(25, [
            'gender' => Genders::MALE,
            'roles' => ['ROLE_USER'],
            'profile' => ProfileFactory::new(),
        ]);

        UserFactory::createMany(25, [
            'gender' => Genders::FEMALE,
            'roles' => ['ROLE_USER'],
            'profile' => ProfileFactory::new(),
        ]);

        $manager->flush();
    }
}
