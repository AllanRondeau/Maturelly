<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\User;
use App\Enum\Genders;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChatFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $manager->getRepository(User::class)->findOneBy(['email' => 'man@maturelly.com']);

        if (!$user1) {
            throw new \Exception('L\'utilisateur "man@maturelly.com" n\'existe pas.');
        }

        $femaleUsers = $manager->getRepository(User::class)->findBy(['gender' => Genders::FEMALE]);

        if (count($femaleUsers) < 10) {
            throw new \Exception('Il n\'y a pas assez d\'utilisateurs féminins pour créer 10 chats.');
        }

        for ($i = 0; $i < 10; $i++) {
            $user2 = $femaleUsers[array_rand($femaleUsers)];

            $chat = new Chat();
            $chat->setUser1($user1);
            $chat->setUser2($user2);

            $manager->persist($chat);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
