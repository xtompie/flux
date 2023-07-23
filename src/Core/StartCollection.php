<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

class StartCollection extends Collection implements Start
{
    public function start(): void
    {
        $this->each(fn (Start $start) => $start->start());
    }
}
