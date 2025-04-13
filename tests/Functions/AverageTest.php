<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\average;

class AverageTest extends TestCase
{
    #[DataProvider("getAverageTestData")]
    public function testAverage(iterable $iterable, ?int $expected)
    {
        $this->assertSame($expected, average($iterable));
    }

    public static function getAverageTestData(): iterable
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
