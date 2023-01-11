<?php

namespace Escapio\Iterables\Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function assertEqualsIterable(
        iterable $expected,
        iterable $actual,
    ) {
        $iterator = new \MultipleIterator(
            \MultipleIterator::MIT_NEED_ANY | \MultipleIterator::MIT_KEYS_ASSOC,
        );
        $iterator->attachIterator(
            is_array($expected) ? new \ArrayIterator($expected) : $expected,
            0,
        );
        $iterator->attachIterator(
            is_array($actual) ? new \ArrayIterator($actual) : $actual,
            1,
        );

        foreach ($iterator as $keys => [$expected_value, $actual_value]) {
            [$expected_key, $actual_key] = $keys;

            $serialized_actual_value = "some " . gettype($actual_value);
            try {
                $serialized_actual_value = serialize($actual_value);
            } catch (\Throwable) {
            }
            $this->assertNotNull(
                $expected_key,
                sprintf(
                    "Unexpected element: %s => %s",
                    print_r($actual_key, true),
                    $serialized_actual_value,
                ),
            );

            $serialized_expected_value = "some " . gettype($actual_value);
            try {
                $serialized_expected_value = serialize($actual_value);
            } catch (\Throwable) {
            }
            $this->assertNotNull(
                $actual_key,
                sprintf(
                    "Expected element not found: %s => %s",
                    print_r($expected_key, true),
                    $serialized_expected_value,
                ),
            );

            $this->assertEquals($expected_key, $actual_key, "Key mismatch");
            $this->assertEquals(
                $expected_value,
                $actual_value,
                "Value mismatch",
            );
        }
        $this->assertTrue(true); // Empty iterators are equal.
    }
}
