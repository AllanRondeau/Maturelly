<?php

namespace App\Factory;

use App\Entity\Hobby;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Hobby>
 */
final class HobbyFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Hobby::class;
    }

    protected function defaults(): array
    {
        return [
            'name' => self::faker()->word(),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
