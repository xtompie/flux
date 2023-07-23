<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

use Exception;

class Program
{
    public static function new(
        string $name,
        array|Start $start = [],
        array|Input $input = [],
        array|Filter $filter = [],
        array|Output $output = [],
        array|Stop $stop = [],
    ) {
        return new static(
            name: $name,
            starts: new StartCollection(is_array($start) ? $start : [$start]),
            inputs: new InputCollection(is_array($input) ? $input : [$input]),
            filters: new FilterCollection(is_array($filter) ? $filter : [$filter]),
            outputs: new OutputCollection(is_array($output) ? $output : [$output]),
            stops: new StopCollection(is_array($stop) ? $stop : [$stop]),
        );
    }

    public function __construct(
        protected string $name,
        protected StartCollection $starts,
        protected InputCollection $inputs,
        protected FilterCollection $filters,
        protected OutputCollection $outputs,
        protected StopCollection $stops,
    ) {
        if (preg_match('#[a-zA-Z0-9-_]+#', $name) !== 1) {
            throw new Exception("Invalid program name '$name'");
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function setUp(): void
    {
        $this->starts->setUp();
        $this->inputs->setUp();
        $this->filters->setUp();
        $this->outputs->setUp();
        $this->stops->setUp();
    }

    protected function tearDown(): void
    {
        $this->starts->tearDown();
        $this->inputs->tearDown();
        $this->filters->tearDown();
        $this->outputs->tearDown();
        $this->stops->tearDown();
    }

    public function run(): void
    {
        $this->setUp();
        $this->starts->start();
        foreach ($this->inputs->input() as $entry) {
            $entry = $this->filters->filter($entry);
            if ($entry === null) {
                continue;
            }
            $this->outputs->output($entry);
        }
        $this->stops->stop();
        $this->tearDown();
    }
}
