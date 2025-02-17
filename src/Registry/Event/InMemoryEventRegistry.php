<?php

declare(strict_types=1);

namespace AwdEs\Registry\Event;

use AwdEs\Meta\Event\Reading\EventMetaReader;

final class InMemoryEventRegistry implements EventRegistry
{
    /**
     * @var array<string, class-string<\AwdEs\Event\EntityEvent>>
     */
    private array $registry = [];

    public function __construct(
        private readonly EventMetaReader $reader,
    ) {}

    #[\Override]
    public function register(string $eventFqn): void
    {
        $meta = $this->reader->read($eventFqn);
        $this->registry[$meta->name] = $eventFqn;
    }

    #[\Override]
    public function eventFqnFor(string $eventName): string
    {
        return $this->registry[$eventName] ?? throw new Exception\UnknownEventName($eventName);
    }
}
