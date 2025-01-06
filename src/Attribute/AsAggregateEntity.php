<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsAggregateEntity
{
    /**
     * @param string                                       $entityId      a unique ID of an entity in the system
     * @param class-string<\AwdEs\Aggregate\AggregateRoot> $aggregateRoot the aggregate class' fqcn
     */
    public function __construct(
        public string $entityId,
        public string $aggregateRoot,
    ) {}
}
