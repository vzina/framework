<?php
declare (strict_types=1);

namespace OpenEf\Framework\EventDispatcher;

use OpenEf\Container\Collector\AnnotationCollector;
use OpenEf\Container\Config\ConfigInterface;
use OpenEf\Framework\EventDispatcher\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $eventDispatcher = new EventDispatcher();

        $this->registerConfig($eventDispatcher, $container);
        $this->registerAnnotations($eventDispatcher, $container);

        return $eventDispatcher;
    }

    private function registerConfig(EventDispatcher $eventDispatcher, ContainerInterface $container): void
    {
        $config = $container->get(ConfigInterface::class);
        foreach ($config->get('listeners', []) as $listener => $priority) {
            if (is_int($listener)) {
                $listener = $priority;
                $priority = 0;
            }

            if (is_string($listener)) {
                $this->register($eventDispatcher, $container, $listener, $priority);
            }
        }
    }

    private function registerAnnotations(EventDispatcher $eventDispatcher, ContainerInterface $container): void
    {
        foreach (AnnotationCollector::list() as $className => $values) {
            /** @var Listener $annotation */
            if ($annotation = $values['_c'][Listener::class] ?? null) {
                $this->register($eventDispatcher, $container, $className, $annotation->priority);
            }
        }
    }

    private function register(EventDispatcher $eventDispatcher, ContainerInterface $container, string $listener, int $priority = 0): void
    {
        $instance = $container->get($listener);
        if ($instance instanceof ListenerInterface) {
            foreach ($instance->listen() as $event) {
                $eventDispatcher->addListener($event, [$instance, 'process'], $priority);
            }
        }
    }
}
