# Escapio PHP-Iterables

## Description

PHP-Iterables is a simple utility library for PHP
to provide an easy and consistent way of using iterables,
no matter which type of iterable you work on: arrays,
[iterators](https://www.php.net/manual/en/class.iterator.php)
or [generators](https://www.php.net/manual/en/class.generator.php).

Furthermore, it allows you to chain several iterator functions in a
fluent, easier-to-read way.

## Features

Check out more detailed examples in the [examples](examples.md) file.

This library includes common functions like

- [map](examples.md#map)
- [filter](examples.md#filter)
- [reduce](examples.md#reduce)

```php
$double_iterable = map($iterable_of_numbers, fn($number) => $number * 2);
```

```php
$filtered_iterable = filter($iterable_of_numbers, fn($number) => $number < 5);
```

```php
$iterable = function () {
    yield 1;
    yield 2;
}
toArray($iterable); // [1, 2]
```

The [iterable-Builder](examples.md#builder) allow you to combine these
functions in a fluent syntax:

```php
(new \Escapio\Iterables\Builder())
  ->from(['Alice', 'Bob', 'Chuck'])
  ->map(strtolower(...))
  ->filter(fn ($name) => $name !== 'chuck')
  ->loop(function ($name) {
    echo $name . PHP_EOL;
  });
  // "alice"
  // "bob"
```

## Installation

Install with [composer](https://getcomposer.org/):

```sh
composer require escapio/php-iterables
```

## Contributing

Feel free to create feature request or report bugs via
[GitHub](https://github.com/escapio/php-iterables/issues/new).

If you like to contribute, make sure that all tests and code style rules are
satisfied, otherwise the CI will fail.

## Tests

Command for executing the [PHPUnit](https://phpunit.de/) tests:

```bash
composer test
```

### Code-Style

This library uses [PHP CS Fixer](https://cs.symfony.com/) for code formatting.
Run the formatter with:

```bash
composer code-style:fix
```

Also see the [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md).

## Changelog

See [CHANGELOG.md](CHANGELOG.md)

## License

PHP-Iterables is made available under the MIT License (MIT). Please see
[License File](LICENSE) for more information.
