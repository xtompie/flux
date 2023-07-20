<?php

declare(strict_types=1);

namespace Xtompie\Flux;

use Generator;

interface Input
{
    public function input(): Generator;
}
