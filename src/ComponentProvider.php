<?php

declare(strict_types=1);

namespace OpenEf\Framework;

use OpenEf\Container\Container;
use OpenEf\Framework\Contract\ApplicationInterface;
use OpenEf\Framework\EventDispatcher\EventDispatcherFactory;
use Psr\EventDispatcher\EventDispatcherInterface;

class ComponentProvider
{

    public function __invoke(Container $container): array
    {
        return [
            'scan' => [
                'paths' => [
                    __DIR__,
                ],
                'dependencies' => [
                    ApplicationInterface::class => ApplicationFactory::class,
                    EventDispatcherInterface::class => EventDispatcherFactory::class,
                ],
            ],
        ];
    }
}
