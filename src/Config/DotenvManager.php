<?php
declare (strict_types=1);

namespace OpenEf\Framework\Config;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\AdapterInterface;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

class DotenvManager
{
    protected static AdapterInterface $adapter;

    protected static Dotenv $dotenv;

    protected static array $cachedValues;

    public static function load(array $paths): void
    {
        if (isset(static::$cachedValues)) {
            return;
        }

        static::$cachedValues = static::getDotenv($paths)->load();
    }

    public static function reload(array $paths, bool $force = false): void
    {
        if (! isset(static::$cachedValues)) {
            static::load($paths);

            return;
        }

        foreach (static::$cachedValues as $deletedEntry => $value) {
            static::getAdapter()->delete($deletedEntry);
        }

        static::$cachedValues = static::getDotenv($paths, $force)->load();
    }

    protected static function getDotenv(array $paths, bool $force = false): Dotenv
    {
        if (isset(static::$dotenv) && ! $force) {
            return static::$dotenv;
        }

        return static::$dotenv = Dotenv::create(
            RepositoryBuilder::createWithNoAdapters()
                ->addAdapter(static::getAdapter($force))
                ->immutable()
                ->make(),
            $paths
        );
    }

    protected static function getAdapter(bool $force = false): AdapterInterface
    {
        if (isset(static::$adapter) && ! $force) {
            return static::$adapter;
        }

        return static::$adapter = PutenvAdapter::create()->get();
    }
}
