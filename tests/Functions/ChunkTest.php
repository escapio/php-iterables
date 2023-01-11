<?php

namespace Escapio\Iterables\Tests\Functions;

use Escapio\Iterables\Tests\TestCase;

use function Escapio\Iterables\chunk;

class ChunkTest extends TestCase
{
    public function testIteratorChunk()
    {
        $inner_iterator = new \ArrayIterator([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $iterator = chunk($inner_iterator, 3);

        $iterator->rewind();
        $this->assertTrue($iterator->valid());
        $this->assertSame([1, 2, 3], array_values($iterator->current()));

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame([4, 5, 6], array_values($iterator->current()));

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame([7, 8, 9], array_values($iterator->current()));

        $iterator->next();
        $this->assertFalse($iterator->valid());
    }

    public function testIteratorChunkRest()
    {
        $inner_iterator = new \ArrayIterator([1, 2, 3, 4, 5, 6, 7]);
        $iterator = chunk($inner_iterator, 5);

        $iterator->rewind();
        $this->assertTrue($iterator->valid());
        $this->assertSame([1, 2, 3, 4, 5], $iterator->current());

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame([6, 7], array_values($iterator->current()));

        $iterator->next();
        $this->assertFalse($iterator->valid());
    }

    public function testIteratorChunkEmpty()
    {
        $iterator = chunk(new \EmptyIterator(), 5);
        $iterator->rewind();
        $this->assertFalse($iterator->valid());
    }

    public function testIteratorChunkException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            '$chunk_size must be greater than 1, 0 given',
        );
        $iterator = chunk(new \EmptyIterator(), 0);
        $iterator->valid();
    }

    public function testIteratorChunkKeys()
    {
        $inner_iterator = new \ArrayIterator([
            "first" => 1,
            "second" => 2,
            "third" => 3,
        ]);
        $iterator = chunk($inner_iterator, 2);

        $iterator->rewind();
        $this->assertTrue($iterator->valid());
        $this->assertSame(["first" => 1, "second" => 2], $iterator->current());

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame(["third" => 3], $iterator->current());

        $iterator->next();
        $this->assertFalse($iterator->valid());
    }
}
