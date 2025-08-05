<?php
/**
 * Functions.php
 * PHP version 7
 *
 * @package open-ef
 * @author  weijian.ye
 * @contact yeweijian@eyugame.com
 * @link    https://github.com/vzina
 */
declare (strict_types=1);

namespace OpenEf\Framework;


use Closure;

/**
 * Return the default value of the given value.
 * @template TValue
 * @template TReturn
 *
 * @param (Closure(TValue):TReturn)|TValue $value
 * @return ($value is Closure ? TReturn : TValue)
 */
function value(mixed $value, ...$args)
{
    return $value instanceof Closure ? $value(...$args) : $value;
}

/**
 * Gets the value of an environment variable.
 *
 * @param string $key
 * @param null|mixed $default
 */
function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return value($default);
    }
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }
    if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
        return substr($value, 1, -1);
    }
    return $value;
}