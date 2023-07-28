<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Xtompie\Flux\Filter\SkipContainingFilter;

class SkipContainingFilterTest extends TestCase
{
    public function testSkip()
    {
        // given
        $filter = SkipContainingFilter::new('foobar');
        $entry = "log foobarbaz";

        // when
        $result = $filter->filter($entry);

        // then
        $this->assertNull($result);
    }

    public function testDontSkip()
    {
        // given
        $filter = SkipContainingFilter::new('fooo');
        $entry = "log foobarbaz";

        // when
        $result = $filter->filter($entry);

        // then
        $this->assertNotNull($result);
    }
}
