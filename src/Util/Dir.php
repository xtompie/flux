<?php

declare(strict_types=1);

namespace Xtompie\Flux\Util;

use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Dir
{
    public static function files(string $dir): Generator
    {
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
            $path = $file->getPathname();
            if (is_file($path)) {
                yield $path;
            }
        }
    }

    public static function touch(string $dir): void
    {
        if (is_dir($dir)) {
            return;
        }
        mkdir($dir, 0777, true);
    }

    public static function ensureEndSlash(string $dir): string
    {
        if (substr($dir, -1) === '/') {
            return $dir;
        }

        return $dir . '/';
    }
}
