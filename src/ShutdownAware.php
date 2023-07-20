<?php

declare(strict_types=1);

namespace Xtompie\Flux;

interface ShutdownAware
{
    public function shutdown(): void;
}
