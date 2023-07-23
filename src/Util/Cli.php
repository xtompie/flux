<?php

declare(strict_types=1);

namespace Xtompie\Flux\Util;

class Cli
{
    public static function success(string $str): void
    {
        echo "\033[32m$str \033[0m\n";
    }

    public static function info(string $str): void
    {
        echo "\033[36m$str \033[0m\n";
    }

    public static function warning(string $str): void
    {
        echo "\033[33m$str \033[0m\n";
    }

    public static function error(string $str): void
    {
        echo "\033[31m$str \033[0m\n";
    }

    public static function run(string $command): void
    {
        static::info($command);
        system($command);
    }
}
