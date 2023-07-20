<?php

declare(strict_types=1);

namespace Xtompie\Flux;

interface Filter
{
    public function filter(string $entry): ?string;
}
