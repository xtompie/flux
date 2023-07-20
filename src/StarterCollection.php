<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class StarterCollection implements Starter
{
    public function each(callable $fn): static
    {
        foreach ($this->collection as $i) {
            $fn($i);
        }
        return $this;
    }

    public function __construct(
        protected array $collection
    ) {
    }

    public function start(): void
    {
        $this->each(fn (Starter $starter) => $starter->start());
    }

    public function provide(string $program): void
    {
        $this->each(fn (object $object) => Providers::provide($program, 'starter', $object));
    }

    public function shutdown(): void
    {
        $this->each(function (object $object) {
            if (!$object instanceof ShutdownAware) {
                return;
            }
            $object->shutdown();
        });
    }
}
