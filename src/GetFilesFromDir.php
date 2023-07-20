<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class GetFilesFromDir
{
    public static function get(string $dir): Generator
    {
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
            $path = $file->getPathname();
            if (is_file($path)) {
                yield $path;
            }
        }

    }
}
