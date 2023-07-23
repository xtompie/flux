<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

use Generator;

/**
 * @property Input[] $collection
 */
class InputCollection extends Collection implements Input
{
    public function input(): Generator
    {
        foreach ($this->collection as $input) {
            foreach($input->input() as $entry) {
                yield $entry;
            }
        }
    }
}
