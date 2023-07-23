<?php

declare(strict_types=1);

namespace Xtompie\Flux\Output;

use Xtompie\Flux\Core\Output;
use Xtompie\Flux\Core\SetUp;
use Xtompie\Flux\Util\Dir;

class FileOutput implements Output, SetUp
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

    public function setUp(): void
    {
        Dir::touch(dirname($this->file));
    }

    public function output(string $entry): void
    {
        file_put_contents($this->file, "$entry\n", FILE_APPEND);
    }
}
