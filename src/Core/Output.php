<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

interface Output
{
    public function output(string $entry): void;
}
