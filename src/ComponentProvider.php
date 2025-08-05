<?php

declare(strict_types=1);

namespace OpenEf\Framework;

use OpenEf\Container\Container;
use OpenEf\Framework\Contract\ApplicationInterface;
use OpenEf\Framework\Contract\ConfigInterface;

class ComponentProvider
{

    public function __invoke(Container $container): void
    {
        $container[ApplicationInterface::class] = function () {
            return new Application();
        };

        $container->extend(ConfigInterface::class, fn(ConfigInterface $config) => $config->merge([
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'collectors' => [],
                    'class_map' => [],
                ],
            ],
        ]));
    }
}
