<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

/**
 * @property Finish[] $collection
 */
class FinishCollection extends Collection implements Finish
{
    public function finish(): void
    {
        $this->each(fn (Finish $finish) =>  $finish->finish());
    }
}
