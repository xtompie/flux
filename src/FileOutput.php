<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class FileOutput implements Output
{
    public function __construct(
        protected string $file,
    ) {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function output(string $entry): void
    {
        file_put_contents($this->file, "$entry\n", FILE_APPEND);
    }
}
