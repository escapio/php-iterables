<?php

namespace Escapio\Iterables\Tests;

use Escapio\Iterables\Builder;

/**
 * @author bgr
 */
class BuilderTest extends \Escapio\Iterables\Tests\TestCase
{
    public function testMap()
    {
        $this->assertEqualsIterable(
            [2, 4, 42],
            (new Builder())
                ->from([1, 2, 21])
                ->map(fn ($number) => 2 * $number)
                ->build(),
        );
    }

    public function testFilter()
    {
        $this->assertEqualsIterable(
            [1 => 2],
            (new Builder())
                ->from([1, 2, 21])
                ->filter(fn ($number) => $number % 2 == 0)
                ->build(),
        );
    }

    public function testFilterWithoutArgument()
    {
        $this->assertEqualsIterable(
            [1 => 2],
            (new Builder())
                ->from([0, 2, 0])
                ->filter()
                ->build(),
        );
    }

    public function testChunk()
    {
        $this->assertEqualsIterable(
            [[0 => 1, 1 => 2], [2 => 21]],
            (new Builder())
                ->from([1, 2, 21])
                ->chunk(2)
                ->build(),
        );
    }

    public function testMerge()
    {
        $this->assertEqualsIterable(
            [1, 2, "a" => 21],
            (new Builder())
                ->from([[1, 2], ["a" => 21]])
                ->merge()
                ->build(),
        );
    }

    public function testMergeRenumbered()
    {
        $this->assertEqualsIterable(
            [1, 2, 21],
            (new Builder())
                ->from([[1, 2], ["a" => 21]])
                ->mergeRenumbered()
                ->build(),
        );
    }

    public function testMultiply()
    {
        $this->assertEqualsIterable(
            [[1, "a"], [1, "b"], [2, "a"], [2, "b"], [21, "a"], [21, "b"]],
            (new Builder())
                ->from([1, 2, 21])
                ->multiply(["a", "b"])
                ->build(),
        );
    }

    public function testAppend()
    {
        $this->assertEqualsIterable(
            [1, "a" => 2, 3, "b" => 4, 5 => 5, 6],
            (new Builder())
                ->from([1, "a" => 2, 3])
                ->append(["b" => 4, 5 => 5], [6 => 6])
                ->build(),
        );
    }

    public function testMapAndFilter()
    {
        $this->assertEqualsIterable(
            [1 => 4, 2 => 6],
            (new Builder())
                ->from([1, 2, 3])
                ->map(function ($number) {
                    return 2 * $number;
                })
                ->filter(function ($number) {
                    return $number > 3;
                })
                ->build(),
        );
    }

    public function testReuseBuilder()
    {
        $builder = new Builder();
        $builder
            ->from([1, 2, 3])
            ->map(function ($number) {
                return 2 * $number;
            })
            ->build();
        $this->assertEqualsIterable(
            [2 => 21],
            $builder
                ->from([1, 2, 21])
                ->filter(function ($number) {
                    return $number > 3;
                })
                ->build(),
        );
    }

    public function testLimit()
    {
        $this->assertEqualsIterable(
            [1, 2, 3],
            (new Builder())
                ->from([1, 2, 3, 4])
                ->limit(3)
                ->build(),
        );
    }

    public function testBuildArray(): void
    {
        $this->assertEquals(
            [1, 2, 3],
            (new Builder())->from(new \ArrayIterator([1, 2, 3]))->buildArray(),
        );
    }

    public function testBuildArrayWithUseKeys(): void
    {
        $generator = function (): iterable {
            yield "a" => 1;
            yield "b" => 2;
            yield "c" => 3;
        };

        $this->assertEquals(
            [1, 2, 3],
            (new Builder())->from($generator())->buildArray($use_keys = false),
        );
    }

    public function testReindex(): void
    {
        $this->assertEqualsIterable(
            [2 => 1, 4 => 2, 6 => 3],
            (new Builder())
                ->from([1, 2, 3])
                ->reindex(function ($item) {
                    return $item * 2;
                })
                ->build(),
        );
    }

    public function testReindexWithoutParameter(): void
    {
        $this->assertEqualsIterable(
            [1, 2, 3],
            (new Builder())
                ->from([2 => 1, 4 => 2, 6 => 3])
                ->reindex()
                ->build(),
        );
    }

    public function testGroupBy(): void
    {
        $this->assertEqualsIterable(
            [
                "integer" => [0 => 1, 1 => 2, 2 => 3],
                "string" => [3 => "a", 4 => "b", 5 => "c"],
                "double" => [6 => 123.0, 7 => 55.0],
            ],
            (new Builder())
                ->from([1, 2, 3, "a", "b", "c", 123.0, 55.0])
                ->groupBy(fn ($item) => gettype($item))
                ->build(),
        );
    }

    public function testGroupChunk(): void
    {
        $this->assertEqualsIterable(
            [[2, [2, 23]], [2, [2 => 32]]],
            (new Builder())
                ->from([2, 23, 32])
                ->groupedChunk(size: 2, group_fn: fn ($number) => $number % 3, )
                ->build(),
        );
    }

    public function testLoop(): void
    {
        $items = [];

        (new Builder())
            ->from(new \ArrayIterator(["a" => 1, "b" => 2, "c" => 3]))
            ->loop(function ($value, $key) use (&$items) {
                $items[$key] = $value * 2;
            });

        $this->assertEquals(["a" => 2, "b" => 4, "c" => 6], $items);
    }

    public function testReduce(): void
    {
        $result = (new Builder())
            ->from(new \ArrayIterator([1, 2, 3]))
            ->reduce(fn ($sum, $curr) => $sum + $curr, 0);

        $this->assertEquals(6, $result);
    }
}
