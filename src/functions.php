<?php

namespace Escapio\Iterables;

/**
 * Iterates over chunks of the desired size from the given iterable, analog to
 * array_chunk.
 *
 * WARNING: if keys are repeated within the same chunk, *items will be lost*.
 *
 * TODO: return generators instead of arrays als chunks to fix the problem.
 */
function chunk(iterable $iterable, int $chunk_size): \Generator
{
    if ($chunk_size < 1) {
        throw new \InvalidArgumentException(
            "\$chunk_size must be greater than 1, $chunk_size given",
        );
    }

    $chunk = [];
    foreach ($iterable as $key => $item) {
        $chunk[$key] = $item;
        if (count($chunk) >= $chunk_size) {
            yield $chunk;
            $chunk = [];
        }
    }

    if (!empty($chunk)) {
        yield $chunk;
    }
}

/**
 * The opposite operation as above. Given a traversable of traversables, unify
 * them as they was just one.
 *
 * This one preserves keys.
 */
function merge(iterable $iterators): \Generator
{
    foreach ($iterators as $iterator) {
        yield from $iterator;
    }
}

/**
 * Like merge() but source keys are ignored and
 * all items get a new incresing number key
 */
function mergeRenumbered(iterable $iterators): \Generator
{
    foreach ($iterators as $iterator) {
        foreach ($iterator as $item) {
            yield $item;
        }
    }
}

/**
 * Generates the cartesian product of the given N iterables.
 */
function multiply(iterable $first, iterable ...$iterators): iterable
{
    if (count($iterators) === 0) {
        foreach ($first as $item) {
            yield [$item];
        }
        return;
    }

    [$second] = $iterators;
    if (count($iterators) === 1) {
        foreach ($first as $first_item) {
            foreach ($second as $second_item) {
                yield [$first_item, $second_item];
            }
        }
        return;
    }

    if (count($iterators) > 1) {
        $rest = array_slice($iterators, 1);
        foreach (multiply($first, $second) as $combination) {
            foreach (multiply([$combination], ...$rest) as $recombination) {
                yield array_merge(
                    $recombination[0],
                    array_slice($recombination, 1),
                );
            }
        }
    }
}

/**
 * Gives an iterator of items for which the callback returns true
 */
function filter(
    iterable $iterable,
    callable $filter_callback = null,
): \Generator {
    $filter_callback = $filter_callback ?: fn ($thing) => $thing;
    foreach ($iterable as $key => $item) {
        if ($filter_callback($item, $key)) {
            yield $key => $item;
        }
    }
}

/**
 * Gives an iterator of items which have been mapped by the
 * given callback
 */
function map(iterable $iterable, callable $map_callback): \Generator
{
    foreach ($iterable as $key => $item) {
        yield $key => $map_callback($item, $key);
    }
}

/**
 * Iterates and calls given consumer for every item
 */
function loop(iterable $iterable, callable $consumer): void
{
    foreach ($iterable as $key => $item) {
        $consumer($item, $key);
    }
}

function reduce(
    iterable $iterable,
    callable $reduce_callback,
    $initial = null,
): mixed {
    foreach ($iterable as $key => $item) {
        $initial = $reduce_callback($initial, $item, $key);
    }
    return $initial;
}

function find(iterable $iterable, callable $find_callback): mixed
{
    foreach ($iterable as $key => $item) {
        if ($find_callback($item, $key)) {
            return $item;
        }
    }
    return null;
}

function any(iterable $iterable, callable $callback): bool
{
    foreach ($iterable as $key => $item) {
        if ($callback($item, $key)) {
            return true;
        }
    }
    return false;
}

function not(callable $callback): callable
{
    return function (...$args) use ($callback) {
        return !$callback(...$args);
    };
}

function every(iterable $iterable, callable $callback): bool
{
    return !any($iterable, not($callback));
}

function sum(iterable $iterable)
{
    return reduce(
        $iterable,
        fn ($acc, $item) => $acc + $item,
        0,
    );
}

function average(iterable $iterable): float|int|null
{
    $count = 0;
    $sum = reduce(
        $iterable,
        function ($acc, $item) use (&$count) {
            $count++;
            return $acc + $item;
        },
        0,
    );

    if ($count === 0) {
        return null;
    }

    return $sum / $count;
}

function toArray(iterable $iterable, $use_keys = true): array
{
    if (is_array($iterable)) {
        return $use_keys ? $iterable : array_values($iterable);
    }
    return iterator_to_array($iterable, $use_keys);
}

function limit(iterable $iterable, int $limit): iterable
{
    $count = 0;
    if ($limit < 1) {
        return;
    }
    foreach ($iterable as $key => $item) {
        yield $key => $item;
        $count++;
        if ($count >= $limit) {
            return;
        }
    }
}

/**
 * Given an iterable and a callback, returns a new iterable with the index
 * mapped by the callback. Like map for keys.
 *
 * If called with no callback, it'll just reindex the iterable with integers
 * starting on 0 (like array_values).
 */
function reindex(iterable $iterable, ?callable $callback = null): iterable
{
    $callback = $callback ?: getCounterCallback();
    foreach ($iterable as $key => $item) {
        yield $callback($item, $key) => $item;
    }
}

function getCounterCallback(int $start = 0): callable
{
    return function () use (&$start) {
        return $start++;
    };
}

/**
 * Interweaves multiple iterables.
 *
 * Iterates over the given iterables and yields the next element of each of them in a round-robin style.
 * Once an iterable is empty it will be skipped.
 * E.g: these iterables ["a", "b"] and [1, 2, 3] would result in an iterable with these elements:
 * 0 => "a"
 * 0 => 1
 * 1 => "b"
 * 1 => 2
 * 2 => 3
 */
function interweave(iterable ...$iterables): iterable
{
    $iterables = array_map(
        fn ($i) => is_array($i) ? new \ArrayIterator($i) : $i,
        $iterables,
    );

    do {
        $did_yield = false;
        foreach ($iterables as $iterable) {
            if (!$iterable->valid()) {
                continue;
            }

            yield $iterable->key() => $iterable->current();
            $iterable->next();
            $did_yield = true;
        }
    } while ($did_yield);
}

/**
 * Yields chunks that share the same value returned by the grouping function.
 *
 * Note that it will group *contiguous* elements with the same value, in order
 * to be memory efficient. Elements should be sorted accordingly.
 */
function groupBy(iterable $iterable, callable $group_fn): iterable
{
    $buffer = [];
    $last_value = null;

    foreach ($iterable as $key => $item) {
        if (empty($buffer)) {
            $last_value = $group_fn($item, $key);
            $buffer[$key] = $item;
            continue;
        }

        $new_value = $group_fn($item, $key);
        if ($new_value !== $last_value) {
            yield $last_value => $buffer;
            $buffer = [];
            $last_value = $new_value;
        }

        $buffer[$key] = $item;
    }

    if (!empty($buffer)) {
        yield $last_value => $buffer;
    }
}

/**
 * Group the items of an iterable by given group function.
 * The grouped buckets are limited to given size.
 *
 * @param iterable $iterable the iterable which elements should be grouped
 * @param int $size          the size of the buckets
 * @param callable $group_fn the function which decides to which bucket an element belongs to: fn($item, $key): mixed
 * @return iterable          with each element in the form of an array [GROUP_KEY, ARRAY_OF_ELEMENTS]
 *                           where the GROUP_KEY is the result of $group_fn($item, $key).
 *                           Because of the chunk size each group_key can occur several times in the result.
 */
function groupedChunk(
    iterable $iterable,
    int $size,
    callable $group_fn,
): iterable {
    $buffers = [];
    foreach ($iterable as $key => $item) {
        $group = $group_fn($item, $key);
        $buffers[$group] ??= [];
        $buffers[$group][$key] = $item;
        if (count($buffers[$group]) >= $size) {
            yield [$group, $buffers[$group]];
            $buffers[$group] = [];
        }
    }

    foreach ($buffers as $group => $buffer) {
        if (!empty($buffer)) {
            yield [$group, $buffer];
        }
    }
}
