<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class StorageProvider implements Provider
{
    public static function provide(string $program, string $type, object $object): void
    {
        if (!$object instanceof StorageAware) {
            return;
        }

        $identify = json_encode([
            'class' => $object::class,
            'program' => $program,
            'type' => $type,
            'object' => $object->identify(),
        ]);

        $hash = sha1($identify);
        $prefix = "var/storage/program/$program/$type/$hash";
        $dir = "$prefix/";
        $id = "$prefix.id";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!is_file($id)) {
            file_put_contents($id, $identify);
        }

        $storage = new Storage(path: $dir);

        $object->setStorage($storage);
    }
}
