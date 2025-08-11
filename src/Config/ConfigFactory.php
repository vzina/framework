<?php
declare (strict_types=1);

namespace OpenEf\Framework\Config;

use Illuminate\Support\Arr;
use OpenEf\Container\Config\ConfigInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ConfigFactory
{
    public static function load(ContainerInterface $container): void
    {
        if (! defined('BASE_PATH')) {
            throw new \RuntimeException('Undefined constant: BASE_PATH');
        }

        if (file_exists(BASE_PATH . '/.env')) {
            DotenvManager::load([BASE_PATH]);
        }

        $container->extend(ConfigInterface::class, static function (ConfigInterface $sc) {
            $configPath = BASE_PATH . '/config';
            $config = self::readConfig($configPath . '/config.php');
            $autoloadConfig = self::readPaths([$configPath . '/autoload']);
            $merged = array_merge_recursive($config, ...$autoloadConfig);

            return $sc->merge($merged);
        });
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
