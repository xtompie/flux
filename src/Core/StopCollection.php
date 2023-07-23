<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

class StopCollection extends Collection implements Stop
{
    public function stop(): void
    {
        $this->each(fn (Stop $stop) => $stop->stop());
    }
}
