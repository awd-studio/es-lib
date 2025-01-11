<?php

declare(strict_types=1);

namespace AwdEs\Event;

/**
 * @implements \IteratorAggregate<\AwdEs\Event\EntityEvent>
 */
final readonly class EventStream implements \IteratorAggregate, \Countable
{
    public function __construct(
        private EventCollection $eventCollection = new InMemoryEventCollection(),
    ) {}

    public function append(EntityEvent $event): void
    {
        $this->eventCollection->attach($event);
    }

    public function isEmpty(): bool
    {
        return 0 === $this->eventCollection->count();
    }

    public function emit(): \Generator
    {
        foreach ($this as $event) {
            yield $event;
            $this->eventCollection->detach($event);
        }
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        yield from $this->eventCollection->sorted();
    }

    #[\Override]
    public function count(): int
    {
        return $this->eventCollection->count();
    }
}
