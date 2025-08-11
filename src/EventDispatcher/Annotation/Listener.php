<?php
declare (strict_types=1);

namespace OpenEf\Framework\EventDispatcher\Annotation;

use Attribute;
use OpenEf\Container\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_CLASS)]
class Listener extends AbstractAnnotation
{
    public function __construct(public int $priority = 0)
    {
    }
}
