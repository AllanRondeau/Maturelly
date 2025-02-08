<?php

namespace App\Factory;

use App\Entity\Profile;
use Faker\Factory as FakerFactory;
use Faker\Factory as FakerFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Profile>
 */
final class ProfileFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Profile::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = FakerFactory::create('fr_FR');

        $faker = FakerFactory::create('fr_FR');

        return [
            'firstName' => $faker->firstName(),
            'familyName' => $faker->lastName(),
            'address' => $faker->streetAddress(),
            'city' => $faker->city(),
            'region' => $faker->region(), // @phpstan-ignore-line
            'postalCode' => $faker->postcode(),
            'country' => 'FR',
            'description' => $faker->optional(0.8)->text(),
            'birthday' => $faker->dateTimeBetween('1920-01-01', '2005-12-31'),
            'description' => $faker->optional(0.8)->text(),
            'birthday' => $faker->dateTimeBetween('1920-01-01', '2005-12-31'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Profile $profile): void {})
        ;
    }
}
