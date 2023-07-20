<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class Rsync
{
    public static function run(string $source, string $destination): void
    {
        system("rsync -r --delete " . escapeshellarg($source) . " " . escapeshellarg($destination));
    }
}
