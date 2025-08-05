<?php
/**
 * ConfigInterface.php
 * PHP version 7
 *
 * @package open-ef
 * @author  weijian.ye
 * @contact yeweijian@eyugame.com
 * @link    https://github.com/vzina
 */
declare (strict_types=1);

namespace OpenEf\Framework\Contract;

interface ConfigInterface
{
    public function has(string $key): bool;

    public function set(string $key, $value = null);

    public function get(string $key, $default = null);

    public function merge($key, array $value = []);
}
