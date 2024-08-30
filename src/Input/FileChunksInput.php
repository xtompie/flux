<?php

declare(strict_types=1);

namespace Xtompie\Flux\Input;

use Generator;
use Xtompie\Flux\Core\Input;
use Xtompie\Flux\Util\File;

class FileChunksInput implements Input
{
    public static function new(string $file, string $pattern): static
    {
        return new static(
            file: $file,
            pattern: $pattern,
        );
    }

    public function __construct(
        protected string $file,
        protected string $pattern,
    ) {
    }
    public function input(): Generator
    {
        if (!is_file($this->file)) {
            return;
        }
        foreach (File::chunks($this->file, $this->pattern) as $entry) {
            yield $entry;
        }
    }
}
