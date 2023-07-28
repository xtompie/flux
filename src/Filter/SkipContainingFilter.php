<?php

declare(strict_types=1);

namespace Xtompie\Flux\Filter;

use Xtompie\Flux\Core\Filter;

class SkipContainingFilter implements Filter
{
    public static function new(string $containing): static
    {
        return new static(containing: $containing);
    }

    public function __construct(
        protected string $containing
    ) {
    }

    public function filter(string $entry): ?string
    {
        if (strpos($entry, $this->containing) !== false) {
            return null;
        }

        return $entry;
    }
}