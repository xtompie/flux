<?php

declare(strict_types=1);

namespace Xtompie\Flux\Input;

use Generator;
use Xtompie\Flux\Core\Input;
use Xtompie\Flux\Util\File;

class FileLinesInput implements Input
{
    public static function new(string $file): static
    {
        return new static(
            file: $file,
        );
    }

    public function __construct(
        protected string $file,
    ) {
    }

    public function input(): Generator
    {
        if (!is_file($this->file)) {
            return;
        }
        foreach (File::lines($this->file) as $entry) {
            yield $entry;
        }
    }
}
