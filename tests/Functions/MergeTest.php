<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\merge;
use function Escapio\Iterables\mergeRenumbered;

class MergeTest extends TestCase
{
    public function testIteratorMerge()
    {
        $this->assertEqualsIterable(
            (function () {
                yield 0 => 1;
                yield 1 => 2;
                yield 2 => 3;
                yield 0 => 4;
                yield 1 => 5;
                yield 2 => 6;
                yield 0 => 7;
                yield 1 => 8;
                yield 2 => 9;
            })(),
            merge([[1, 2, 3], [4, 5, 6], [7, 8, 9]]),
        );
    }

    public function testIteratorMergeRenumbered()
    {
        $this->assertEqualsIterable(
            [1, 2, 3, 4, 5, 6, 7, 8, 9],
            mergeRenumbered([[1, 2, 3], [4, 5, 6], [7, 8, 9]]),
        );
    }
}
