<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\groupBy;
use function Escapio\Iterables\groupedChunk;

class GroupByTest extends TestCase
{
    #[DataProvider("getGroupByData")]
    public function testGroupBy($iterable, $group_fn, $expected): void
    {
        $this->assertEqualsIterable($expected, groupBy($iterable, $group_fn));
    }

    public static function getGroupByData(): iterable
    {
        yield "Empty array" => [
            "iterable" => [],
            "group_fn" => fn ($i) => $i,
            "expected" => [],
        ];

        yield "One value" => [
            "iterable" => ["a"],
            "group_fn" => fn ($i) => $i,
            "expected" => ["a" => ["a"]],
        ];

        yield "Two elements of the same type" => [
            "iterable" => ["a", "b"],
            "group_fn" => fn ($i) => "group",
            "expected" => ["group" => ["a", "b"]],
        ];

        yield "Group array elements by their type" => [
            "iterable" => [1, 2, 3, "a", "b", "c", 123.0, 55.0],
            "group_fn" => fn ($item) => gettype($item),
            "expected" => [
                "integer" => [0 => 1, 1 => 2, 2 => 3],
                "string" => [3 => "a", 4 => "b", 5 => "c"],
                "double" => [6 => 123.0, 7 => 55.0],
            ],
        ];
    }

    #[DataProvider("dataGroupedChunk")]
    public function testGroupedChunk($iterable, $size, $group_fn, $expected)
    {
        $this->assertEqualsIterable(
            $expected,
            groupedChunk($iterable, $size, $group_fn),
        );
    }

    public static function dataGroupedChunk(): iterable
    {
        yield "empty" => [[], 5, fn ($number) => $number % 3, []];
        yield "single value" => [
            "iterable" => [23],
            "size" => 5,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [[2, [23]]],
        ];
        yield "all values of same type in separate chunks" => [
            "iterable" => [2, 23],
            "size" => 1,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [[2, [2]], [2, [1 => 23]]],
        ];
        yield "all values of same type in same chunk" => [
            "iterable" => [2, 23],
            "size" => 2,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [[2, [2, 23]]],
        ];
        yield "all values of same type in some chunks" => [
            "iterable" => [2, 23, 32],
            "size" => 2,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [[2, [2, 23]], [2, [2 => 32]]],
        ];
        yield "all values of different types in separate chunks" => [
            "iterable" => [1, 2, 3, 4],
            "size" => 1,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [[1, [1]], [2, [1 => 2]], [0, [2 => 3]], [1, [3 => 4]]],
        ];
        yield "all values of different types in some chunks" => [
            "iterable" => [1, 2, 3, 4],
            "size" => 2,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [[1, [1, 3 => 4]], [2, [1 => 2]], [0, [2 => 3]]],
        ];
        yield "several types in several chunks" => [
            "iterable" => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            "size" => 2,
            "group_fn" => fn ($number) => $number % 3,
            "expected" => [
                [1, [1, 3 => 4]],
                [2, [1 => 2, 4 => 5]],
                [0, [2 => 3, 5 => 6]],
                [1, [6 => 7]],
                [2, [7 => 8]],
                [0, [8 => 9]],
            ],
        ];
    }
}
