<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Exception;

class Program
{
    protected bool $provided = false;

    public static function new(
        string $name,
        array|Starter $starter = [],
        array|Input $input = [],
        array|Filter $filter = [],
        array|Output $output = [],
        array|Finalizer $finalizer = [],
    ) {
        return new static(
            name: $name,
            starters: new StarterCollection(is_array($starter) ? $starter : [$starter]),
            inputs: new InputCollection(is_array($input) ? $input : [$input]),
            filters: new FilterCollection(is_array($filter) ? $filter : [$filter]),
            outputs: new OutputCollection(is_array($output) ? $output : [$output]),
            finalizers: new FinalizerCollection(is_array($finalizer) ? $finalizer : [$finalizer]),
        );
    }

    public function __construct(
        protected string $name,
        protected StarterCollection $starters,
        protected InputCollection $inputs,
        protected FilterCollection $filters,
        protected OutputCollection $outputs,
        protected FinalizerCollection $finalizers,
    ) {
        if (preg_match('#[a-zA-Z0-9-_]+#', $name) !== 1) {
            throw new Exception("Invalid program name '$name'");
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function provide(): void
    {
        if ($this->provided) {
            return;
        }
        $this->provided = true;

        $this->starters->provide($this->name);
        $this->inputs->provide($this->name);
        $this->filters->provide($this->name);
        $this->outputs->provide($this->name);
        $this->finalizers->provide($this->name);
    }

    protected function shutdown(): void
    {
        $this->starters->shutdown();
        $this->inputs->shutdown();
        $this->filters->shutdown();
        $this->outputs->shutdown();
        $this->finalizers->shutdown();
    }

    public function run(): void
    {
        $this->provide();
        $this->starters->start();
        foreach ($this->inputs->input() as $entry) {
            $entry = $this->filters->filter($entry);
            if ($entry === null) {
                continue;
            }
            $this->outputs->output($entry);
        }
        $this->finalizers->finalize();
        $this->shutdown();
    }
}
