<?php

declare(strict_types=1);

namespace Xtompie\Flux\Output;

use Xtompie\Flux\Core\Output;

class EchoOutput implements Output
{
    public static function new(): static
    {
        return new static();
    }

    public function output(string $entry): void
    {
        echo $entry . PHP_EOL;
    }
}
