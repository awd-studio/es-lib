<?php

declare(strict_types=1);

namespace AwdEs\Registry\Event;

final class InMemoryEventRegistry implements EventRegistry
{
    /**
     * @param array<string, class-string<\AwdEs\Aggregate\Entity>> $registry
     */
    public function __construct(
        private array $registry = [],
    ) {}

    #[\Override]
    public function register(string $eventFqn, string $eventName): void
    {
        $this->registry[$eventName] = $eventFqn;
    }

    #[\Override]
    public function eventFqnFor(string $eventName): string
    {
        return $this->registry[$eventName] ?? throw new Exception\UnknownEventName($eventName);
    }
}
