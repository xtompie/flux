<?php

declare(strict_types=1);

namespace Xtompie\Flux;

interface Provider
{
    public static function provide(string $program, string $type, object $object): void;
}
