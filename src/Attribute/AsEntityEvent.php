<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsEntityEvent
{
    /**
     * @param string                                       $eventId       a unique ID of an entity in the system
     * @param class-string<\AwdEs\Aggregate\AggregateRoot> $aggregateRoot the aggregate class' fqcn
     */
    public function __construct(
        public string $eventId,
        public string $aggregateRoot,
    ) {}
}
