<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class EchoFilter implements Filter
{
    public function filter(string $entry): ?string
    {
        echo "$entry\n";
        return $entry;
    }
}
