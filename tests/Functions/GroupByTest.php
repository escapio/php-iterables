<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\groupBy;
use function Escapio\Iterables\groupedChunk;

class GroupByTest extends TestCase
{
    /**
     * @dataProvider getGroupByData
     */
    public function testGroupBy($iterable, $group_fn, $expected): void
    {
        $this->assertEqualsIterable($expected, groupBy($iterable, $group_fn));
    }

    public function getGroupByData(): iterable
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

    /**
     * @dataProvider dataGroupedChunk
     */
    public function testGroupedChunk($iterable, $size, $group_fn, $expected)
    {
        $this->assertEqualsIterable(
            $expected,
            groupedChunk($iterable, $size, $group_fn),
        );
    }

    public function dataGroupedChunk(): iterable
    {
        yield "empty" => [[], 5, fn ($number) => $number % 3, []];
        yield "single value" => [
            [23],
            5,
            fn ($number) => $number % 3,
            [[2, [23]]],
        ];
        yield "all values of same type in separate chunks" => [
            [2, 23],
            1,
            fn ($number) => $number % 3,
            [[2, [2]], [2, [1 => 23]]],
        ];
        yield "all values of same type in same chunk" => [
            [2, 23],
            2,
            fn ($number) => $number % 3,
            [[2, [2, 23]]],
        ];
        yield "all values of same type in some chunks" => [
            [2, 23, 32],
            2,
            fn ($number) => $number % 3,
            [[2, [2, 23]], [2, [2 => 32]]],
        ];
        yield "all values of different types in separate chunks" => [
            [1, 2, 3, 4],
            1,
            fn ($number) => $number % 3,
            [[1, [1]], [2, [1 => 2]], [0, [2 => 3]], [1, [3 => 4]]],
        ];
        yield "all values of different types in some chunks" => [
            [1, 2, 3, 4],
            2,
            fn ($number) => $number % 3,
            [[1, [1, 3 => 4]], [2, [1 => 2]], [0, [2 => 3]]],
        ];
        yield "several types in several chunks" => [
            [1, 2, 3, 4, 5, 6, 7, 8, 9],
            2,
            fn ($number) => $number % 3,
            [
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
