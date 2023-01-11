<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\reduce;

class ReduceTest extends TestCase
{
    public function testIteratorReduceWithEmptyIterator()
    {
        $this->assertNull(
            reduce([], function ($carry, $item) {
                return $carry + $item;
            }),
        );
    }

    public function testIteratorReduce()
    {
        $this->assertSame(
            42,
            reduce([19, 23], function ($carry, $item) {
                return $carry + $item;
            }),
        );
    }
}
