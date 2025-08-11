<?php

declare (strict_types=1);

namespace OpenEf\Framework\Command\Event;

use OpenEf\Framework\Command\Command;
use Throwable;

class FailToHandle extends Event
{
    public function __construct(Command $command, protected ?Throwable $throwable = null)
    {
        parent::__construct($command);
    }

    public function getThrowable(): ?Throwable
    {
        return $this->throwable;
    }
}
