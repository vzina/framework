<?php

declare (strict_types=1);

namespace OpenEf\Framework\Command\Event;

use OpenEf\Framework\Command\Command;

abstract class Event
{
    public function __construct(protected Command $command)
    {
    }

    public function getCommand(): Command
    {
        return $this->command;
    }
}
