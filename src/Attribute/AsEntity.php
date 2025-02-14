<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsEntity implements AwdEsAttribute
{
    /**
     * @param string                                       $name    a unique ID of an entity in the system
     * @param class-string<\AwdEs\Aggregate\AggregateRoot> $rootFqn the aggregate class' fqcn
     */
    public function __construct(
        public string $name,
        public string $rootFqn,
    ) {}
}
