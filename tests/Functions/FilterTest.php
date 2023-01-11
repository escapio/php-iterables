<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\filter;

class FilterTest extends TestCase
{
    public function testIteratorFilter()
    {
        $this->assertEqualsIterable(
            [1 => 2, 3 => 4],
            filter([1, 2, 3, 4], fn ($i) => $i % 2 == 0),
        );
    }

    public function testIteratorFilterWithNoCallback()
    {
        $this->assertEqualsIterable(
            [1 => 2, 3 => 4],
            filter([null, 2, null, 4]),
        );
    }
}
