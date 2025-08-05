<?php
/**
 * Config.php
 * PHP version 7
 *
 * @package open-ef
 * @author  weijian.ye
 * @contact yeweijian@eyugame.com
 * @link    https://github.com/vzina
 */
declare (strict_types=1);

namespace OpenEf\Framework\Config;

use Illuminate\Support\Arr;
use OpenEf\Framework\Contract\ConfigInterface;

class Config implements ConfigInterface
{
    public function __construct(protected array $items = [])
    {
    }

    public function has(string $key): bool
    {
        return Arr::has($this->items, $key);
    }

    public function set(string $key, $value = null)
    {
        Arr::set($this->items, $key, $value);
    }

    public function get(string $key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    public function merge($key, array $value = [])
    {
        if (is_array($key)) {
            $this->items = array_merge_recursive($this->items, $key);
        } elseif (is_string($key)) {
            $this->items[$key] = array_merge_recursive((array)$this->get($key), $value);
        }

        return $this;
    }
}
