<?php

namespace App\DataFixtures;

use App\Enum\Genders;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_ADMIN'],
            'gender' => Genders::MALE,
        ]);

        UserFactory::createOne([
            'email' => 'man@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'gender' => Genders::MALE,
        ]);

        UserFactory::createOne([
            'email' => 'woman@maturelly.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'gender' => Genders::FEMALE,
        ]);

        $manager->flush();

        UserFactory::createMany(25, [
            'gender' => Genders::MALE,
            'roles' => ['ROLE_USER'],
        ]);

        UserFactory::createMany(25, [
            'gender' => Genders::FEMALE,
            'roles' => ['ROLE_USER'],
        ]);

        $manager->flush();
    }
}
