<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

use Exception;

/**
 * @property Program[] $collection
 */
class ProgramCollection
{
    public function __construct(
        protected array $collection
    ) {
        if (!$this->hasUniqueNames()) {
            throw new Exception("Programs must have unique names");
        }
    }

    public function each(callable $fn): static
    {
        foreach ($this->collection as $i) {
            $fn($i);
        }
        return $this;
    }

    public function filter(?callable $fn = null): static
    {
        return new static(array_values(array_filter($this->collection, $fn, ARRAY_FILTER_USE_BOTH)));
    }

    public function runAll(): void
    {
        $this->each(fn (Program $program) => $program->run());
    }

    public function run($name): void
    {
        $this
            ->filter(fn (Program $program) => $program->name() === $name)
            ->each(fn (Program $program) => $program->run())
        ;
    }

    public function contains(string $program): bool
    {
        return $this
            ->filter(fn (Program $i) => $i->name() === $program)
            ->any()
        ;
    }

    protected function hasUniqueNames(): bool
    {
        $names = array_map(fn (Program $program) => strtolower($program->name()), $this->collection);
        return count($names) === count(array_unique($names));
    }

    protected function any(): bool
    {
        return (bool) $this->collection;
    }
}
