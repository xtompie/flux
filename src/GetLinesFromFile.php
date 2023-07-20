<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;

class GetLinesFromFile
{
    public static function get(string $file): Generator
    {
        $r = fopen($file, 'r');
        while (($line = fgets($r)) !== false) {
            yield rtrim($line);
        }
        fclose($r);
    }
}
