<?php

declare(strict_types=1);

namespace Xtompie\Flux;

class FinalizerCollection implements Finalizer
{
    public function __construct(
        protected array $collection
    ) {
    }

    public function each(callable $fn): static
    {
        foreach ($this->collection as $i) {
            $fn($i);
        }
        return $this;
    }

    public function provide(string $program): void
    {
        $this->each(fn (object $object) => Providers::provide($program, 'finalizer', $object));
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

    public function finalize(): void
    {
        $this->each(fn (Finalizer $finalizer) => $finalizer->finalize());
    }
}
