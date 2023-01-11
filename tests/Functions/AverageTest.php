<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\average;

class AverageTest extends TestCase
{
    /** @dataProvider getAverageTestData */
    public function testAverage(iterable $iterable, ?int $expected)
    {
        $this->assertSame($expected, average($iterable));
    }

    public function getAverageTestData(): iterable
    {
        yield "empty iterable" => [
            "iterable" => [],
            "expected" => null,
        ];
        yield "non empty iterable" => [
            "iterable" => [1, 2, 3],
            "expected" => 2,
        ];
    }
}
