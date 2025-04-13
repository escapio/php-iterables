<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\interweave;

class InterweaveTest extends TestCase
{
    #[DataProvider("getInterweaveData")]
    public function testInterweave(array $iterables, iterable $expected): void
    {
        $this->assertEqualsIterable($expected, interweave(...$iterables));
    }

    public static function getInterweaveData(): iterable
    {
        yield "no arrays" => [
            "iterables" => [],
            "expected" => [],
        ];

        yield "interweave two arrays" => [
            "iterables" => [["a", "b", "c"], [1, 2]],
            "expected" => (function () {
                yield 0 => "a";
                yield 0 => 1;
                yield 1 => "b";
                yield 1 => 2;
                yield 2 => "c";
            })(),
        ];

        yield "interweave two arrays, second is longer" => [
            "iterables" => [["a", "b"], [1, 2, 3]],
            "expected" => (function () {
                yield 0 => "a";
                yield 0 => 1;
                yield 1 => "b";
                yield 1 => 2;
                yield 2 => 3;
            })(),
        ];

        yield "interweave three arrays" => [
            "iterables" => [["a", "b", "c"], [1, 2, 3], ["x", "y", "z"]],
            "expected" => (function () {
                yield 0 => "a";
                yield 0 => 1;
                yield 0 => "x";
                yield 1 => "b";
                yield 1 => 2;
                yield 1 => "y";
                yield 2 => "c";
                yield 2 => 3;
                yield 2 => "z";
            })(),
        ];
    }
}
