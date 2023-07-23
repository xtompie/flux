<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

/**
 * @property Filter[] $collection
 */
class FilterCollection extends Collection implements Filter
{
    public function filter(string $entry): ?string
    {
        foreach ($this->collection as $filter) {
            $entry = $filter->filter($entry);
            if ($entry === null) {
                return null;
            }
        }

        return $entry;
    }
}
