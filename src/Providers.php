<?php

declare(strict_types=1);

namespace Xtompie\Flux;

final class Providers implements Provider
{
    protected static $providers = [
        StorageProvider::class,
    ];

    public static function register(Provider $provider): void
    {
        static::$providers[] = $provider;
    }

    public static function provide(string $program, string $type, object $object): void
    {
        foreach (static::$providers as $provider) {
            $provider::provide($program, $type, $object);
        }
    }

}
