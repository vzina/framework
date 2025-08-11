<?php
declare (strict_types=1);

namespace OpenEf\Framework\EventDispatcher;

interface ListenerInterface
{
    public function listen(): array;

    public function process(object $event): void;
}
