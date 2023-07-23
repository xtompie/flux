<?php

declare(strict_types=1);

namespace Xtompie\Flux\Finish;

use Xtompie\Flux\Core\Finish;
use Xtompie\Flux\Util\Cli;
use Xtompie\Flux\Util\Dir;

class CountFilesLinesFinish implements Finish
{
    public static function new(string $dir): static
    {
        return new static(
            dir: $dir,
        );
    }

    public function __construct(
        protected string $dir,
    ) {
    }

    public function finish(): void
    {
        $dir = Dir::ensureEndSlash($this->dir);
        Cli::run('wc -l '. escapeshellarg($dir) . '*');
    }
}
