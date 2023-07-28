<?php

declare(strict_types=1);

namespace Xtompie\Flux\Filter;

use Xtompie\Flux\Core\Filter;

class SkipPrefixFilter implements Filter
{
    public static function new(string $prefix): static
    {
        return new static(prefix: $prefix);
    }

    public function __construct(
        protected string $prefix
    ) {
    }

    public function filter(string $entry): ?string
    {
        if (str_starts_with($entry, $this->prefix)) {
            return null;
        }

        return $entry;
    }
}