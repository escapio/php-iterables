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

    public function map(callable $callback): self
    {
        $this->iterable = map($this->iterable, $callback);
        return $this;
    }

    public function filter(callable $callback = null): self
    {
        $this->iterable = filter($this->iterable, $callback);
        return $this;
    }

    public function chunk(int $size): self
    {
        $this->iterable = chunk($this->iterable, $size);
        return $this;
    }

    public function groupedChunk(int $size, callable $group_fn): self
    {
        $this->iterable = groupedChunk($this->iterable, $size, $group_fn);
        return $this;
    }

    public function merge(): self
    {
        $this->iterable = merge($this->iterable);
        return $this;
    }

    public function mergeRenumbered(): self
    {
        $this->iterable = mergeRenumbered($this->iterable);
        return $this;
    }

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

    public function limit(int $limit): self
    {
        $this->iterable = limit($this->iterable, $limit);
        return $this;
    }

    public function reindex(?callable $callback = null): self
    {
        $this->iterable = reindex($this->iterable, $callback);
        return $this;
    }

    public function groupBy(callable $group_fn): self
    {
        $this->iterable = groupBy($this->iterable, $group_fn);
        return $this;
    }

    public function build(): iterable
    {
        $iterable = $this->iterable;
        $this->iterable = null;
        return $iterable;
    }

    public function buildArray($use_keys = true): array
    {
        return toArray($this->build(), $use_keys);
    }

    public function loop(callable $consumer): void
    {
        loop($this->iterable, $consumer);
    }

    public function reduce(callable $callback, $initial = null): mixed
    {
        return reduce($this->iterable, $callback, $initial);
    }
}
