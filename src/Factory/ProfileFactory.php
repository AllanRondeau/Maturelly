<?php

namespace App\Factory;

use App\Entity\Profile;
use App\Entity\User;
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
        return [
            'firstName' => self::faker()->firstName(),
            'familyName' => self::faker()->lastName(),
            'address' => self::faker()->address(),
            'city' => self::faker()->city(),
            'region' => self::faker()->state(),
            'country' => 'FR',
            'description' => self::faker()->optional()->text(),
            'birthday' => self::faker()->dateTimeBetween('1920-01-01', '2005-12-31'),
            'code' => self::faker()->uuid(),
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
