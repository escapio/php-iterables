<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\multiply;

class MultiplyTest extends TestCase
{
    /**
     * Given: two or more arrays
     * When: the multiply function is called
     * Then: the cartesian product is returned
     *
     */
    #[DataProvider("getMultiplyData")]
    public function testMultiply(
        iterable $iterators,
        iterable $expected,
    ): void {
        $this->assertEqualsIterable($expected, multiply(...$iterators));
    }

    public static function getMultiplyData(): iterable
    {
        yield "one dimension" => [
            "iterators" => [["a", "b"]],
            "expected" => [["a"], ["b"]],
        ];

        yield "two dimensions" => [
            "iterators" => [["a", "b"], [1, 2, 3]],
            "expected" => [
                ["a", 1],
                ["a", 2],
                ["a", 3],
                ["b", 1],
                ["b", 2],
                ["b", 3],
            ],
        ];

        yield "three dimensions" => [
            "iterators" => [["a", "b"], [1, 2, 3], ["foo", "bar"]],
            "expected" => [
                ["a", 1, "foo"],
                ["a", 1, "bar"],
                ["a", 2, "foo"],
                ["a", 2, "bar"],
                ["a", 3, "foo"],
                ["a", 3, "bar"],
                ["b", 1, "foo"],
                ["b", 1, "bar"],
                ["b", 2, "foo"],
                ["b", 2, "bar"],
                ["b", 3, "foo"],
                ["b", 3, "bar"],
            ],
        ];

        yield "four dimensions" => [
            "iterators" => [["a", "b"], [1, 2], ["foo", "bar"], ["X", "Y"]],
            "expected" => [
                ["a", 1, "foo", "X"],
                ["a", 1, "foo", "Y"],
                ["a", 1, "bar", "X"],
                ["a", 1, "bar", "Y"],
                ["a", 2, "foo", "X"],
                ["a", 2, "foo", "Y"],
                ["a", 2, "bar", "X"],
                ["a", 2, "bar", "Y"],
                ["b", 1, "foo", "X"],
                ["b", 1, "foo", "Y"],
                ["b", 1, "bar", "X"],
                ["b", 1, "bar", "Y"],
                ["b", 2, "foo", "X"],
                ["b", 2, "foo", "Y"],
                ["b", 2, "bar", "X"],
                ["b", 2, "bar", "Y"],
            ],
        ];
    }
}
