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
    - [SetUp and TearDown](#setup-and-teardown)
    - [Built-in components](#built-in-components)
    - [Usage](#usage)
    - [Log monitor example](#log-monitor-example)
    - [Project tool example](#project-tool-example)

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

### Usage

`flux.php`:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Xtompie\Flux\Core\Machine;
use Xtompie\Flux\Core\Program;
use Xtompie\Flux\Filter\OnceFilter;
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

### Log monitor example

Application that will collect logs from many application or serwers.

`flux.php`:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Xtompie\Flux\Core\Machine;
use Xtompie\Flux\Core\Program;
use Xtompie\Flux\Filter\OnceFilter;
use Xtompie\Flux\Input\LinesInput;
use Xtompie\Flux\Output\FileOutput;
use Xtompie\Flux\Start\RsyncStart;
use Xtompie\Flux\Finish\CountFilesLinesFinish;

Machine::new(
    program: [
        Program::new(
            name: 'aaa',
            start: RsyncStart::new('user@aaa.example.ccom:/var/logs/nginx/aaa.errorlog', 'var/aaa/input'),
            input: LinesInput::new('var/aaa/input/'),
            filter: OnceFilter::new('var/aaa/once/'),
            output: FileOutput::new('log/aaa.log'),
        ),
        Program::new(
            name: 'bbb',
            start: RsyncStart::new('user@bbb.example.ccom:/var/logs/nginx/bbb.errorlog', 'var/bbb/input'),
            input: LinesInput::new('var/bbb/input/'),
            filter: OnceFilter::new('var/bbb/once/'),
            output: FileOutput::new('log/bbb.log'),
        ),
    ],
    finish: CountFilesLinesFinish::new('log/'),
)
    ->run()
;
```

Then in shell `php flux.php runall`.

With `OnceFilter` the `log/` directory will bahave like inbox.
Only new entries will be stored in `log/`.
Entries from `log/` can be manually deleted.
`CountFilesLinesFinish` will tell how meany new entries are in `log/`.

### Project tool example

Tool in project that will help track application error from test and prod server.

Create folder `tools/log` in project root directory.
`cd tools/log`.
`composer require xtompie/flux`
This will output:
> No composer.json in current directory, do you want to use the one at ../../? [Y,n]?
Type `n`.
This should create `composer.json`, `composer.lock` and `vendor`.
Create `.gitignore` with contents:

```text
/log/
/var/
/vendor/
```

Create `flux.php` and modify it for your needs:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Xtompie\Flux\Core\Machine;
use Xtompie\Flux\Core\Program;
use Xtompie\Flux\Filter\OnceFilter;
use Xtompie\Flux\Input\LinesInput;
use Xtompie\Flux\Output\FileOutput;
use Xtompie\Flux\Start\RsyncStart;
use Xtompie\Flux\Stop\CountFileLinesStop;

Machine::new([
    Program::new(
        name: $name = 'dev',
        start: RsyncStart::new('user@host-dev:/var/log/nginx/application.error.log', "var/$name/input"),
        input: LinesInput::new("var/$name/input/"),
        filter: OnceFilter::new("var/$name/once/"),
        output: FileOutput::new("log/$name.log"),
        stop: CountFileLinesStop::new("log/$name.log"),
    ),
    Program::new(
        name: $name = 'test',
        start: RsyncStart::new('user@host-prod:/var/log/nginx/application.error.log', "var/$name/input"),
        input: LinesInput::new("var/$name/input/"),
        filter: OnceFilter::new("var/$name/once/"),
        output: FileOutput::new("log/$name.log"),
        stop: CountFileLinesStop::new("log/$name.log"),
    ),
])
    ->run()
;
```

Then in project root directory in `composer.json` add scripts:

```json
{
    "scripts": {
        "log-dev": "cd tools/log && composer install && php flux.php run dev",
        "log-test": "cd tools/log && composer install && php flux.php run test",
    },
}
```

Now from your project root directory with `composer log-dev` u can easily fetch new error logs.
