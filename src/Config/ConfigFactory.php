<?php
/**
 * ConfigFactory.php
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
use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ConfigFactory
{
    public static function load(ContainerInterface $container): void
    {
        if (file_exists(BASE_PATH . '/.env')) {
            DotenvManager::load([BASE_PATH]);
        }

        $container[ConfigInterface::class] = function () {
            $configPath = BASE_PATH . '/config';
            $config = self::readConfig($configPath . '/config.php');
            $autoloadConfig = self::readPaths([$configPath . '/autoload']);
            $merged = array_merge_recursive($config, ...$autoloadConfig);

            return new Config($merged);
        };
    }

    private static function readConfig(string $configPath): array
    {
        $config = [];
        if (file_exists($configPath) && is_readable($configPath)) {
            $config = require $configPath;
        }
        return is_array($config) ? $config : [];
    }

    private static function readPaths(array $paths): array
    {
        $configs = [];
        $finder = new Finder();
        $finder->files()->in($paths)->name('*.php');
        foreach ($finder as $file) {
            $config = [];
            $key = implode('.', array_filter([
                str_replace('/', '.', $file->getRelativePath()),
                $file->getBasename('.php'),
            ]));
            Arr::set($config, $key, require $file->getRealPath());
            $configs[] = $config;
        }

        return $configs;
    }
}
