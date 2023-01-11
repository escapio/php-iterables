<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\sum;

class SumTest extends TestCase
{
    public function testSum()
    {
        $this->assertSame(15, sum([1, 2, 3, 4, 5]));
    }
}
