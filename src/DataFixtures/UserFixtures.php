<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_ADMIN'],
            'gender' => 'm',
        ]);

        UserFactory::createOne([
            'email' => 'man@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'gender' => 'm',
        ]);

        UserFactory::createOne([
            'email' => 'woman@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'gender' => 'f',
        ]);

        $manager->flush();

        UserFactory::createMany(25, [
            'gender' => 'm',
            'roles' => ['ROLE_USER'],
        ]);

        UserFactory::createMany(25, [
            'gender' => 'f',
            'roles' => ['ROLE_USER'],
        ]);

        $manager->flush();
    }
}
