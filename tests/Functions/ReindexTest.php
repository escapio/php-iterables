<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use PHPUnit\Framework\Attributes\DataProvider;

use function Escapio\Iterables\reindex;
use function Escapio\Iterables\getCounterCallback;

class ReindexTest extends TestCase
{
    public function testReindexWithDefaultReindexFunction(): void
    {
        $this->assertEqualsIterable(
            [1, 2, 3],
            reindex([2 => 1, 4 => 2, 6 => 3]),
        );
    }

    public function testReindex(): void
    {
        $this->assertEqualsIterable(
            [2 => 1, 4 => 2, 6 => 3],
            reindex([1, 2, 3], fn ($item) => $item * 2),
        );
    }

    #[DataProvider("getCounterCallbackData")]
    public function testGetCounterCallback(array $params, array $calls): void
    {
        $counter = getCounterCallback(...$params);
        foreach ($calls as $expected) {
            $this->assertSame($expected, $counter());
        }
    }

    public static function getCounterCallbackData(): iterable
    {
        yield "no initial argument" => [
            "params" => [],
            "calls" => [0, 1, 2, 3, 4, 5],
        ];

        yield "initial argument is zero" => [
            "params" => [0],
            "calls" => [0, 1, 2, 3, 4, 5],
        ];

        yield "initial argument is 3" => [
            "params" => [3],
            "calls" => [3, 4, 5, 6, 7, 8],
        ];
    }
}
