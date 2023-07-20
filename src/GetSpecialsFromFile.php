<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;

class GetSpecialsFromFile
{
    public static function get(string $file, string $pattern): Generator
    {
        $r = fopen($file, 'r');
        $buffor = null;
        while (($line = fgets($r)) !== false) {
            if (preg_match("#^$pattern#", $line) === 1) {
                if ($buffor !== null) {
                    yield $buffor;
                    $buffor = '';
                }
            }
            $buffor .= $line;
        }

        if ($buffor !== null) {
            yield $buffor;
        }

        fclose($r);
    }
}
