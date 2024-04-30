<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\toArray;

class ToArrayTest extends TestCase
{
    #[DataProvider("getToArrayData")]
    public function testToArray(iterable $iterable): void
    {
        $this->assertEquals([1, 2, 3], toArray($iterable));
    }

    public static function getToArrayData(): iterable
    {
        $generator = function (): iterable {
            yield 1;
            yield 2;
            yield 3;
        };

        yield "array" => ["iterable" => [1, 2, 3]];
        yield "iterator" => ["iterable" => new \ArrayIterator([1, 2, 3])];
        yield "generator" => ["iterable" => $generator()];
    }

    public function testToArrayUseKeys(): void
    {
        $generator = function (): iterable {
            yield "a" => 1;
            yield "b" => 2;
            yield "c" => 3;
        };

        $array = ["a" => 1, "b" => 2, "c" => 3];

        $this->assertEquals(
            ["a" => 1, "b" => 2, "c" => 3],
            toArray($generator()),
        );

        $this->assertEquals(
            [1, 2, 3],
            toArray($generator(), use_keys: false),
        );

        $this->assertEquals(["a" => 1, "b" => 2, "c" => 3], toArray($array));

        $this->assertEquals([1, 2, 3], toArray($array, use_keys: false));
    }
}
