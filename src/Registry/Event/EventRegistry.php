<?php

declare(strict_types=1);

namespace AwdEs\Registry\Event;

interface EventRegistry
{
    /**
     * @param class-string<\AwdEs\Event\EntityEvent> $eventFqn
     */
    public function register(string $eventFqn): void;

    /**
     * @return class-string<\AwdEs\Event\EntityEvent>
     *
     * @throws Exception\UnknownEventName
     */
    public function eventFqnFor(string $eventName): string;
}
