<?php

declare(strict_types=1);

namespace Xtompie\Flux;

interface Identifiable
{
    public function identify(): string;
}
