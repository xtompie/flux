<?php

declare(strict_types=1);

namespace Xtompie\Flux\Input;

use Generator;
use Xtompie\Flux\Core\Input;
use Xtompie\Flux\Util\Dir;
use Xtompie\Flux\Util\File;

class ChunksInput implements Input
{
    public static function new(string $source, string $pattern): static
    {
        return new static(
            source: $source,
            pattern: $pattern,
        );
    }

    public function __construct(
        protected string $source,
        protected string $pattern,
    ) {
    }
    public function input(): Generator
    {
        if (!is_dir($this->source)) {
            return;
        }
        foreach (Dir::files($this->source) as $file) {
            foreach (File::chunks($file, $this->pattern) as $entry) {
                yield $entry;
            }
        }
    }
}
