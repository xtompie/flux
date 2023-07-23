<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

/**
 * @property Output[] $collection
 */
class OutputCollection extends Collection implements Output
{
    public function output(string $entry): void
    {
        $this->each(fn (Output $output) => $output->output($entry));
    }
}
