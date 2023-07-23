<?php

declare(strict_types=1);

namespace Xtompie\Flux\Core;

abstract class Collection implements SetUp, TearDown
{
    public function __construct(
        protected array $collection
    ) {
    }

    public function each(callable $fn): void
    {
        foreach ($this->collection as $i) {
            $fn($i);
        }
    }

    public function setUp(): void
    {
        $this->each(fn (object $object) =>  $object instanceof SetUp ? $object->setUp() : null);
    }

    public function tearDown(): void
    {
        $this->each(fn (object $object) =>  $object instanceof TearDown ?  $object->tearDown() : null);
    }
}
