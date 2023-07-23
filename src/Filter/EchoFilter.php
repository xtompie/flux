<?php

declare(strict_types=1);

namespace Xtompie\Flux\Filter;

use Xtompie\Flux\Core\Filter;

class EchoFilter implements Filter
{
    public static function new(): static
    {
        return new static();
    }

    public function filter(string $entry): ?string
    {
        echo "$entry\n";
        return $entry;
    }
}
