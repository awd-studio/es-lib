<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final readonly class EventHandler
{
    /**
     * @param class-string<\AwdEs\Event\EntityEvent> $eventType
     */
    public function __construct(
        public string $eventType,
    ) {}
}
