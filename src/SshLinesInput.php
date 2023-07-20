<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;

class SshLinesInput implements Input, StorageAware
{
    protected ?Storage $storage = null;

    public function __construct(
        protected string $source,
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
        ]);
    }

    public function input(): Generator
    {
        Rsync::run($this->source, $this->storage->path());

        foreach (GetFilesFromDir::get($this->storage->path()) as $file) {
            foreach (GetLinesFromFile::get($file) as $line) {
                yield $line;
            }
        }
    }
}
