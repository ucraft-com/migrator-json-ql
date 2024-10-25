<?php

declare(strict_types=1);

namespace QLParser\Utils;

use ArrayAccess;
use Closure;

use function is_array;
use function explode;
use function count;
use function array_shift;
use function str_contains;
use function array_key_exists;
use function is_null;
use function is_float;

class Arr
{
    /**
     * @param mixed $data
     *
     * @return bool
     */
    public static function isArray(mixed $data): bool
    {
        return is_array($data);
    }

    // Get a reference to the value at a given path
    public static function &getReference(&$array, $key)
    {
        $keys = explode('.', $key);
        $ref = &$array;
        foreach ($keys as $segment) {
            if (!isset($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }
        return $ref;
    }

    // Set a value at a given path
    public static function set(&$array, $key, $value)
    {
        $ref = &self::getReference($array, $key);
        $ref = $value;
    }

    // Forget a value at a given path
    public static function forget(&$array, $key)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                return;
            }
            $array = &$array[$key];
        }
        unset($array[array_shift($keys)]);
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param \ArrayAccess|array $array
     * @param string|int|float   $key
     *
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        if (is_float($key)) {
            $key = (string)$key;
        }

        return array_key_exists($key, $array);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|int|null    $key
     * @param mixed              $default
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return self::value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $array[$key] ?? self::value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return self::value($default);
            }
        }

        return $array;
    }

    private static function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}
