<?php

declare(strict_types=1);

namespace Xtompie\Flux\Util;

use Generator;

class File
{
    public static function lines(string $file): Generator
    {
        $r = fopen($file, 'r');
        while (($line = fgets($r)) !== false) {
            yield rtrim($line);
        }
        fclose($r);
    }

    public static function chunks(string $file, string $pattern): Generator
    {
        $r = fopen($file, 'r');
        $buffor = null;
        while (($line = fgets($r)) !== false) {
            if (preg_match("#^$pattern#", $line) === 1) {
                if ($buffor !== null) {
                    yield rtrim($buffor);
                    $buffor = '';
                }
            }
            $buffor .= $line;
        }

        if ($buffor !== null) {
            yield rtrim($buffor);
        }

        fclose($r);
    }
}
