<?php

declare(strict_types=1);

namespace Xtompie\Flux;

final class Machine
{
    public static function new(
        array|Program $program,
    ) {
        return new static(
            programs: new ProgramCollection(is_array($program) ? $program : [$program]),
        );
    }

    public function __construct(
        protected ProgramCollection $programs,
    ) {
    }

    public function runAllPrograms(): void
    {
        $this->programs->runAll();
    }

    public function runProgram(string $name): void
    {
        $this->programs->run($name);
    }
}


