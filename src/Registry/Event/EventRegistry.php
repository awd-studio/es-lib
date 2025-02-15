<?php

declare(strict_types=1);

namespace AwdEs\Registry\Event;

interface EventRegistry
{
    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $eventFqn
     */
    public function register(string $eventFqn, string $eventName): void;

    /**
     * @return class-string<\AwdEs\Aggregate\Entity>
     *
     * @throws Exception\UnknownEventName
     */
    public function eventFqnFor(string $eventName): string;
}
