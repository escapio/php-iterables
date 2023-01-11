<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\every;

class EveryTest extends TestCase
{
    public function testEvery(): void
    {
        $this->assertTrue(every([2, 4, 6, 8], fn ($value) => is_int($value)));
        $this->assertFalse(every([2, "4", 6, 8], fn ($value) => is_int($value)));
    }
}
