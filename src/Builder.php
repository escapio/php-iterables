<?php

namespace Escapio\Iterables;

/**
 * Fluid interface to modify iterators
 */
class Builder
{
    private ?iterable $iterable;

    public function from(iterable $iterable): self
    {
        $this->iterable = $iterable;
        return $this;
    }

    /** @see \Escapio\Iterables\map() */
    public function map(callable $callback): self
    {
        $this->iterable = map($this->iterable, $callback);
        return $this;
    }

    /** @see \Escapio\Iterables\filter() */
    public function filter(callable $callback = null): self
    {
        $this->iterable = filter($this->iterable, $callback);
        return $this;
    }

    /** @see \Escapio\Iterables\chunk() */
    public function chunk(int $size): self
    {
        $this->iterable = chunk($this->iterable, $size);
        return $this;
    }

    /** @see \Escapio\Iterables\groupedChunk() */
    public function groupedChunk(int $size, callable $group_fn): self
    {
        $this->iterable = groupedChunk($this->iterable, $size, $group_fn);
        return $this;
    }

    /** @see \Escapio\Iterables\merge() */
    public function merge(): self
    {
        $this->iterable = merge($this->iterable);
        return $this;
    }

    /** @see \Escapio\Iterables\mergeRenumbered() */
    public function mergeRenumbered(): self
    {
        $this->iterable = mergeRenumbered($this->iterable);
        return $this;
    }

    /** @see \Escapio\Iterables\multiply() */
    public function multiply(iterable $iterator): self
    {
        $this->iterable = multiply($this->iterable, $iterator);
        return $this;
    }

    public function append(iterable ...$iterators): self
    {
        $previous = $this->iterable;
        $this->iterable = merge(
            (function () use ($previous, $iterators) {
                yield $previous;
                foreach ($iterators as $iterator) {
                    yield $iterator;
                }
            })(),
        );
        return $this;
    }

    /** @see \Escapio\Iterables\limit() */
    public function limit(int $limit): self
    {
        $this->iterable = limit($this->iterable, $limit);
        return $this;
    }

    /** @see \Escapio\Iterables\reindex() */
    public function reindex(?callable $callback = null): self
    {
        $this->iterable = reindex($this->iterable, $callback);
        return $this;
    }

    /** @see \Escapio\Iterables\groupBy() */
    public function groupBy(callable $group_fn): self
    {
        $this->iterable = groupBy($this->iterable, $group_fn);
        return $this;
    }

    /**
     * Get the final iterable.
     */
    public function build(): iterable
    {
        $iterable = $this->iterable;
        $this->iterable = null;
        return $iterable;
    }

    /**
     * Get the final iterable as an array.
     */
    public function buildArray($use_keys = true): array
    {
        return toArray($this->build(), $use_keys);
    }

    /** @see \Escapio\Iterables\loop() */
    public function loop(callable $consumer): void
    {
        loop($this->iterable, $consumer);
    }

    /** @see \Escapio\Iterables\reduce() */
    public function reduce(callable $callback, $initial = null): mixed
    {
        return reduce($this->iterable, $callback, $initial);
    }
}
