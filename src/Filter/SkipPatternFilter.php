<?php

declare(strict_types=1);

namespace Xtompie\Flux\Filter;

use Xtompie\Flux\Core\Filter;

class SkipPatternFilter  implements Filter
{
    public static function new(string $pattern): static
    {
        return new static(pattern: $pattern);
    }

    public function __construct(
        protected string $pattern
    ) {
    }

    public function filter(string $entry): ?string
    {
        if (preg_match("#$this->pattern#", $entry) === 1) {
            return null;
        }

        return $entry;
    }
}