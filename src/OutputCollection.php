<?php

declare(strict_types=1);

namespace Xtompie\Flux;

/**
 * @property Output[] $collection
 */
class OutputCollection implements Output
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
        $this->each(fn (Output $object) => Providers::provide($program, 'output', $object));
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

    public function output(string $entry): void
    {
        $this->each(fn (Output $output) => $output->output($entry));
    }
}
