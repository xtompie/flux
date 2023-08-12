# flux

Event and Log Management Tool.
Data processing pipeline created in PHP.
Efficiently collect, parse, and transform logs.
Highly customizable and extensible for your needs.

- [flux](#flux)
  - [Requirments](#requirments)
  - [Installation](#installation)
    - [Using skeleton](#using-skeleton)
  - [Docs](#docs)
    - [Machine](#machine)
    - [Program](#program)
    - [Start](#start)
    - [Input](#input)
    - [Filter](#filter)
    - [Output](#output)
    - [Stop](#stop)
    - [Finish](#finish)
    - [Example](#example)
    - [SetUp and TearDown](#setup-and-teardown)
    - [Built-in components](#built-in-components)

## Requirments

- PHP >= 8

## Installation

### Using skeleton

[xtompie/flux-skeleton](https://github.com/xtompie/flux-skeleton)

## Docs

### Machine

Entrypoint of flux is `Machine`.
Machine have programs and finishes.

### Program

Program has unique name, starts, inputs, filters, outputs, stops.

### Start

Each starts is called at beging of program.
They are designed to prepare data.
An example of start can be a rsync which will download data from an external server to a local folder or unpack an archive.
See: [Start](https://github.com/xtompie/flux/blob/master/src/Core/Start.php)

### Input

Next each input is called.
Input generates a entry of type string.
Each generated entry is individually and immediately passed to filters.
An example input can be a generator that generates an entry from each line of file.
See: [Input](https://github.com/xtompie/flux/blob/master/src/Core/Input.php)

### Filter

Entry is passed into each filter.
Filter can modify the entry.
Filter can return null then the entry will not be further processed.
See: [Filter](https://github.com/xtompie/flux/blob/master/src/Core/Filter.php)

### Output

Entry returned from filters is passed to each output.
An example output can append the entry to file.
See: [Output](https://github.com/xtompie/flux/blob/master/src/Core/Output.php)

### Stop

Each stop is called at end of program.
A clean up can be done in stop.
See: [Stop](https://github.com/xtompie/flux/blob/master/src/Core/Stop.php)

### Finish

Machine have finishse.
Finishes are called after the desired program/programs are executed.
It is similar to stop but for machine.
See: [Finish](https://github.com/xtompie/flux/blob/master/src/Core/Finish.php)

### Example

`flux.php`:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Xtompie\Flux\Core\Machine;
use Xtompie\Flux\Core\Program;
use Xtompie\Flux\Filter\OnceFilter;
use Xtompie\Flux\Finish\CountFilesLinesFinish;
use Xtompie\Flux\Input\LinesInput;
use Xtompie\Flux\Output\FileOutput;
use Xtompie\Flux\Start\RsyncStart;
use Xtompie\Flux\Stop\CountFileLinesStop;

Machine::new(
    program: [
        Program::new(
            name: 'default',
            start: RsyncStart::new('user@127.0.0.1:/var/nginx/logs/laravel-*', 'var/default/input'),
            input: LinesInput::new('var/default/input/'),
            filter: OnceFilter::new('var/default/once/'),
            output: FileOutput::new('log/default.log'),
            stop: CountFileLinesStop::new('log/default.log'),
        )
    ]
)
    ->run()
;

```

Then in shell `php flux.php runall`.

Programs can be run by:

- `Machine->runAllPrograms()`
- `Machine->runProgram(string $name)`
- `Machine->run` - then machine reads shell arguments
  - `runall`  - runs all programs e.g. `php flux.php runall`
  - `run <program-name>` - run prgoram by name  e.g.`php flux.php run default`

### SetUp and TearDown

Starts, inputs, filters, outputs and stops can implement
[SetUp](https://github.com/xtompie/flux/blob/master/src/Core/SetUp.php),
[TearDown](https://github.com/xtompie/flux/blob/master/src/Core/TearDown.php)
interfaces.

SetUp is used at the beginning of program startup before starts are called.

TearDown is used at the end of program execution after each stop is called.

### Built-in components

- [Start](https://github.com/xtompie/flux/blob/master/src/Start)
- [Input](https://github.com/xtompie/flux/blob/master/src/Input)
- [Filter](https://github.com/xtompie/flux/blob/master/src/Filter)
- [Output](https://github.com/xtompie/flux/blob/master/src/Output)
- [Stop](https://github.com/xtompie/flux/blob/master/src/Stop)
- [Finish](https://github.com/xtompie/flux/blob/master/src/Finish)
