<?php

declare(strict_types=1);

namespace Xtompie\Flux\Util;

class Rsync
{
    public static function sync(string $src, string $dest): void
    {
        Cli::run("rsync -vr --delete " . escapeshellarg($src) . " " . escapeshellarg($dest));
    }
}
