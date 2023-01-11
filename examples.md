# Examples

- [map](#map)
- [filter](#filter)
- [reduce](#reduce)
- [toArray](#toarray)
- [find](#find)
- [any](#any)
- [every](#every)
- [merge](#merge)
- [mergeRenumbered](#mergerenumbered)
- [chunk](#chunk)
- [multiply](#multiply)
- [limit](#limit)
- [reindex](#reindex)
- [sum](#sum)
- [average](#average)
- [interweave](#interweave)
- [groupBy](#groupby)
- [Builder](#builder)

## Map

Iterate over an iterable and convert each item with specified function.

```php
use function \Escapio\Iterables\map;

$double_iterable = map($iterable_of_numbers, fn($number) => $number * 2);
```

With key
```php
use function \Escapio\Iterables\map;

$modified_keys = map(
  ["a" => "foo", "b" => "bar"],
  fn($value, $key) => strtoupper($key)
);
// ["A", "B"]
```

## Filter

Without filter closure:

```php
use function \Escapio\Iterables\filter;

$not_null_items = filter([null, 2, null, 4]);
// 1 => 2
// 3 => 4
```

With closure:

```php
use function \Escapio\Iterables\filter;

filter([1, 2, 3, 4], fn ($i) => $i % 2 == 0);
// 1 => 2
// 3 => 4
```

## Reduce

```php
use function \Escapio\Iterables\reduce;

reduce([19, 23], fn($carry, $item) => $carry + $item); // 42
reduce([19, 23], fn($carry, $item) => $carry + $item, initial: 1000); // 1042
```

## ToArray

```php
use function \Escapio\Iterables\toArray;

$generator = function (): iterable {
  yield "a" => 1;
  yield "b" => 2;
  yield "c" => 3;
};

toArray($generator); // [1, 2, 3]
toArray($generator, use_keys: true); // ["a" => 1, "b" => 2, "c" => 3]
```

## Find

```php
use function \Escapio\Iterables\find;

$data = [
  ["id" => 42, "name" => "alice"],
  ["id" => 24, "name" => "bob"],
];

find($data, fn($item) => $item["id"] === 42); // ["id" => 42, "name" => "alice"]
find($data, fn($item) => $item === 73); // null
```

## Any

```php
use function \Escapio\Iterables\any;

any([2, 4, 6, 8], "is_string"); // false
any([2, "4", 6, 8], "is_string"); // true
```

## Every

```php
use function \Escapio\Iterables\every;

every([2, 4, 6, 8], "is_string"); // true
every([2, "4", 6, 8], "is_string"); // false
```

## Merge

```php
use function \Escapio\Iterables\merge;

merge([[1, 2, 3], [4, 5, 6], [7, 8, 9]])
// 0 => 1
// 1 => 2
// 2 => 3
// 0 => 4
// 1 => 5
// 2 => 6
// 0 => 7
// 1 => 8
// 2 => 9
```

## MergeRenumbered

```php
use function \Escapio\Iterables\mergeRenumbered;

mergeRenumbered([[1, 2, 3], [4, 5, 6], [7, 8, 9]])
// 0 => 1
// 1 => 2
// 2 => 3
// 3 => 4
// 4 => 5
// 5 => 6
// 6 => 7
// 7 => 8
// 8 => 9
```

## Chunk

```php
use function \Escapio\Iterables\chunk;

chunk([1, 2, 3, 4, 5], chunk_size: 2);
// [1, 2]
// [3, 4]
// [5]
```

## Multiply

```php
use function \Escapio\Iterables\multiply;

multiply(["a", "b"], [1, 2, 3]);
// ["a", 1]
// ["a", 2]
// ["a", 3]
// ["b", 1]
// ["b", 2]
// ["b", 3]
```

## Limit

```php
use function \Escapio\Iterables\limit;

limit(["a", "b", "c"], limit: 2);
// "a"
// "b"
```

## Reindex

```php
use function \Escapio\Iterables\reindex;

reindex([2 => 1, 4 => 2, 6 => 3]); // [1, 2, 3]
reindex(
  [2 => 1, 4 => 2, 6 => 3],
  fn($item, $key) => $key * 2
); // [4 => 1, 8 => 2, 12 => 3]

```

## Sum

```php
use function \Escapio\Iterables\sum;

sum([1, 2, 3, 4, 5]); // 15
```

## Average

```php
use function \Escapio\Iterables\average;

average([1, 2]); // 1.5
```

## Interweave

```php
use function \Escapio\Iterables\interweave;

interweave(["a", "b", "c"], [1, 2]);
// 0 => "a"
// 0 => 1
// 1 => "b"
// 1 => 2
// 2 => "c"
```

## GroupBy

Yields chunks that share the same value returned by the grouping function.

Note that it will group *contiguous* elements with the same value, in order
to be memory efficient. Elements should be sorted accordingly.

```php
use function \Escapio\Iterables\groupBy;

groupBy(
  iterable: [1, 2, 3, "a", "b", "c", 123.0, 55.0],
  group_fn: fn($item) => gettype($item)
);
// "integer" => [0 => 1, 1 => 2, 2 => 3],
// "string" => [3 => "a", 4 => "b", 5 => "c"],
// "double" => [6 => 123.0, 7 => 55.0],
```

## Loop

Iterate over an iterable and call a function on each element

```php
use function \Escapio\Iterables\loop;

loop(
  ["a" => 1, "b" => 2],
  fn($element, $key) => echo "Key: $key, value: $element" . PHP_EOL;
);
// "Key a, value 1"
// "Key b, value 2"
```

## Builder

The Builder allows to combine different of these helper functions to process
an iterable as desired.

```php
(new \Escapio\Iterables\Builder())
  ->from([1, 2, 3])
  ->map(fn($num) => $num * 2)
  ->filter(fn ($num) => $num < 5)
  ->buildArray();
// [2, 4]

(new \Escapio\Iterables\Builder())
  ->from([1, 2, 3])
  ->multiply(["a", "b"])
  ->loop(function ($values) {
    echo sprintf("number: %s, char: %s", $values[0], $values[1])
  });
// "number: 1, char: a"
// "number: 1, char: b"
// "number: 2, char: a"
// "number: 2, char: b"
// "number: 3, char: a"
// "number: 3, char: b"

(new \Escapio\Iterables\Builder())
  ->from([1, 2, 3])
  ->append(["a", "b"])
  ->build();
// 1
// 2
// 3
// "a"
// "b"
```
