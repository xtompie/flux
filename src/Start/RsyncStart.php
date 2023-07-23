<?php

declare(strict_types=1);

namespace Xtompie\Flux\Start;

use Xtompie\Flux\Core\Start;
use Xtompie\Flux\Util\Dir;
use Xtompie\Flux\Util\Rsync;

class RsyncStart implements Start
{
    public static function new(string $src, string $dest): static
    {
        return new static(
            src: $src,
            dest: $dest,
        );
    }

    public function __construct(
        protected string $src,
        protected string $dest,
    ) {
    }

    public function start(): void
    {
        Dir::touch($this->dest);
        Rsync::sync($this->src, $this->dest);
    }
}
