<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;

class SshSpecialInput implements Input, StorageAware
{
    protected ?Storage $storage = null;

    public function __construct(
        protected string $source,
        protected string $pattern,
    ) {
    }

    public function setStorage(Storage $storage): void
    {
        $this->storage = $storage;
    }

    public function identify(): string
    {
        return json_encode([
            'source' => $this->source,
            'pattern' => $this->pattern,
        ]);
    }

    public function input(): Generator
    {
        Rsync::run($this->source, $this->storage->path());

        foreach (GetFilesFromDir::get($this->storage->path()) as $file) {
            foreach (GetSpecialsFromFile::get($file, $this->pattern) as $line) {
                yield $line;
            }
        }
    }
}
