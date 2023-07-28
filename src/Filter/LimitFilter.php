<?php

declare(strict_types=1);

namespace Xtompie\Flux\Filter;

use Xtompie\Flux\Core\Filter;

class LimitFilter  implements Filter
{
    protected $count = 0;

    public static function new(int $limit): static
    {
        return new static(limit: $limit);
    }

    public function __construct(
        protected int $limit
    ) {
    }

    public function filter(string $entry): ?string
    {
        if ($this->count < $this->limit) {
            $this->count++;
            return $entry;
        }

        return null;
    }
}