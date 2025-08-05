<?php
/**
 * ApplicationContext.php
 * PHP version 7
 *
 * @package open-ef
 * @author  weijian.ye
 * @contact yeweijian@eyugame.com
 * @link    https://github.com/vzina
 */
declare (strict_types=1);

namespace OpenEf\Framework;

use OpenEf\Container\Container;

class ApplicationContext
{
    /** @var Container */
    protected static $container;

    /**
     * @param Container $container
     */
    public static function setContainer(Container $container): Container
    {
        return self::$container = $container;
    }

    /**
     * @return Container
     */
    public static function getContainer(): Container
    {
        return self::$container;
    }
}
