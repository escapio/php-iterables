<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\not;

class NotTest extends TestCase
{
    public function testNot(): void
    {
        $is_even = fn (int $num) => $num % 2 === 0;
        $this->assertTrue($is_even(2));
        $this->assertFalse(not($is_even)(2));
    }
}
