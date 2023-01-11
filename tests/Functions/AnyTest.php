<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\any;

class AnyTest extends TestCase
{
    /**
     * Given: an iterable
     * When: the any function is called
     * Then: function returns true if every item passes the test
     */
    public function testAny()
    {
        $this->assertFalse(any([2, 4, 6, 8], fn ($value) => is_string($value)));
        $this->assertTrue(any([2, "4", 6, 8], fn ($value) => is_string($value)));
    }
}
