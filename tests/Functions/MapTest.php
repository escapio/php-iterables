<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\map;

class MapTest extends TestCase
{
    public function testMapModifiesValues()
    {
        $this->assertEqualsIterable(
            ["x" => 2, "y" => 4, "z" => 6],
            map(["x" => 1, "y" => 2, "z" => 3], fn ($i) => 2 * $i),
        );
    }

    public function testMapModifiesValuesUsingKeys()
    {
        $this->assertEqualsIterable(
            ["x" => "x:1", "y" => "y:2", "z" => "z:3"],
            map(["x" => 1, "y" => 2, "z" => 3], fn ($i, $key) => "$key:$i"),
        );
    }
}
