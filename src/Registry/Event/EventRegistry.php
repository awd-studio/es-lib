<?php

declare(strict_types=1);

namespace AwdEs\Registry\Event;

/**
 * @extends \IteratorAggregate<string, class-string<\AwdEs\Event\EntityEvent>>
 */
interface EventRegistry extends \IteratorAggregate
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
