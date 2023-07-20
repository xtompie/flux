<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class SkipEmptyFilter implements Filter
{
    public function filter(string $entry): ?string
    {
        if (trim($entry) === '') {
            return null;
        }

        return $entry;
    }
}
