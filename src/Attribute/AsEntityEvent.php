<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsEntityEvent
{
    /**
     * @param string                                      $eventId    a unique ID of an event in the system
     * @param class-string<\AwdEs\Entity\AggregateEntity> $entityType the entity class' fqcn
     */
    public function __construct(
        public string $eventId,
        public string $entityType,
    ) {}
}
