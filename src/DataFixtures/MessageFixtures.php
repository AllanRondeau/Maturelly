<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $chats = $manager->getRepository(Chat::class)->findAll();

        foreach ($chats as $chat) {
            for ($i = 0; $i < 10; ++$i) {
                $message = new Message();
                $message->setChat($chat);

                $sender = $faker->randomElement([$chat->getUser1(), $chat->getUser2()]);
                $message->setSender($sender);

                $message->setContent($faker->sentence());
                $message->setCreatedAt(new \DateTime());

                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ChatFixtures::class,
        ];
    }
}
