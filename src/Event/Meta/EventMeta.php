<?php

declare(strict_types=1);

namespace AwdEs\Event\Meta;

final readonly class EventMeta
{
    /**
     * @param class-string<\AwdEs\Event\EntityEvent> $entityClass
     */
    public function __construct(
        public string $eventTypeId,
        public string $entityClass,
    ) {}
}
