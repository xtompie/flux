<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;

/**
 * @property Input[] $collection
 */
class InputCollection implements Input
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
        $this->each(fn (Input $object) => Providers::provide($program, 'input', $object));
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

    public function input(): Generator
    {
        foreach ($this->collection as $input) {
            foreach($input->input() as $entry) {
                yield $entry;
            }
        }
    }
}
