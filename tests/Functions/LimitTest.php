<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\limit;

class LimitTest extends TestCase
{
    #[DataProvider("getTestData")]
    public function testLimit(iterable $iterable, int $limit, iterable $expected)
    {
        $this->assertEqualsIterable($expected, limit($iterable, $limit));
    }

    public static function getTestData(): iterable
    {
        yield "empty iterable" => [
            'iterable' => [],
            "limit" => 3,
            'expected' => [],
        ];

        yield "limit equals the iterable size" => [
            'iterable' => (function () {
                yield 1;
                yield 2;
                yield 3;
                throw new \Exception("Never go here");
            })(),
            "limit" => 3,
            'expected' => [1, 2, 3],
        ];

        yield "negative limit" => [
            'iterable' => (function () {
                yield 1;
                yield 2;
                yield 3;
                throw new \Exception("Never go here");
            })(),
            "limit" => -1,
            'expected' => [],
        ];

        yield "limit smaller than the iterable size" => [
            'iterable' => (function () {
                yield 1;
                yield 2;
                yield 3;
                yield 4;
            })(),
            "limit" => 3,
            'expected' => [1, 2, 3],
        ];

        yield "not even loop over cut-off elements" => [
            'iterable' => (function () {
                yield 1;
                yield 2;
                yield 3;
                throw new \Exception("Never go here");
                yield 4;
            })(),
            "limit" => 3,
            'expected' => [1, 2, 3],
        ];
    }
}
