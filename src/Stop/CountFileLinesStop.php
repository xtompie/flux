<?php

declare(strict_types=1);

namespace Xtompie\Flux\Stop;

use Xtompie\Flux\Core\Stop;
use Xtompie\Flux\Util\Cli;

class CountFileLinesStop implements Stop
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

    public function stop(): void
    {
        Cli::run('wc -l '. escapeshellarg($this->file));
    }
}
