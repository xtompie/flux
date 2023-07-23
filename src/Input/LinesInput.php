<?php

declare(strict_types=1);

namespace Xtompie\Flux\Input;

use Generator;
use Xtompie\Flux\Core\Input;
use Xtompie\Flux\Util\Dir;
use Xtompie\Flux\Util\File;

class LinesInput implements Input
{
    public static function new(string $source): static
    {
        return new static(
            source: $source,
        );
    }

    public function __construct(
        protected string $source,
    ) {
    }

    public function input(): Generator
    {
        foreach (Dir::files($this->source) as $file) {
            foreach (File::lines($file) as $entry) {
                yield $entry;
            }
        }
    }
}
