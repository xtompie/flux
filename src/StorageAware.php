<?php

declare(strict_types=1);

namespace Xtompie\Flux;

interface StorageAware extends Identifiable
{
    public function setStorage(Storage $storage): void;
}
