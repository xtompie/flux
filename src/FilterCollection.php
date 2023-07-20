<?php

declare(strict_types=1);

namespace Xtompie\Flux;

/**
 * @property Filter[] $collection
 */
class FilterCollection implements Filter
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
        $this->each(fn (object $object) => Providers::provide($program, 'filter', $object));
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

    public function filter(string $entry): ?string
    {
        foreach ($this->collection as $filter) {
            $entry = $filter->filter($entry);
            if ($entry === null) {
                return null;
            }
        }

        return $entry;
    }
}
