<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\find;

class FindTest extends TestCase
{
    #[DataProvider("getTestData")]
    public function testIteratorFind(iterable $iterable, ?int $expected)
    {
        $this->assertSame(
            $expected,
            find($iterable, fn ($item) => $item == 42),
        );
    }

    public static function getTestData(): iterable
    {
        yield "empty iterable" => [
            'iterable' => [],
            'expected' => null,
        ];
        yield "item not found" => [
            'iterable' => [19, 23],
            'expected' => null,
        ];
        yield "item found" => [
            'iterable' => [19, 42, 17, 42],
            'expected' => 42,
        ];
    }
}
