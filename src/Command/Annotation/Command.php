<?php
declare (strict_types=1);

namespace OpenEf\Framework\Command\Annotation;

use Attribute;
use OpenEf\Container\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_CLASS)]
class Command extends AbstractAnnotation
{
    public function __construct(
        public string $name = '',
        public array $arguments = [],
        public array $options = [],
        public string $description = '',
        public array $aliases = [],
        public ?string $signature = null,
    ) {
    }
}
