<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class Storage
{
    public function __construct(
        protected string $path,
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }
}
