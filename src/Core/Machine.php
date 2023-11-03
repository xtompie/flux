<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

use Xtompie\Flux\Util\Cli;

final class Machine
{
    public static function new(
        array|Program $program,
        array|Finish $finish = [],

    ) {
        return new static(
            programs: new ProgramCollection(is_array($program) ? $program : [$program]),
            finishes: new FinishCollection(is_array($finish) ? $finish : [$finish]),
        );
    }

    public function __construct(
        protected ProgramCollection $programs,
        protected FinishCollection $finishes,
    ) {
    }

    public function runAllPrograms(): void
    {
        $this->programs->rejectPrivate()->runAll();
        $this->finishes->finish();

    }

    public function runProgram(string $name): void
    {
        $this->programs->run($name);
        $this->finishes->finish();
    }

    public function run(): void
    {
        $args = $GLOBALS['argv'];
        $action = $args[1] ?? null;
        if ($action === null) {
            Cli::error('Action required');
            exit(1);
        }
        else if ($action === 'runall') {
            $this->runAllPrograms();
        }
        else if ($action === 'run') {
            $program = $action = $args[2] ?? null;
            if ($program === null) {
                Cli::error('Program name required');
                exit(3);
            }
            if (!$this->programs->contains($program)) {
                Cli::error("Program with name '$program' not found");
                exit(4);
            }
            $this->runProgram($program);
        }
        else {
            Cli::error('Invalid action');
            exit(2);
        }
        exit(0);
    }
}
