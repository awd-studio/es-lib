<?php

declare(strict_types=1);

namespace AwdEs\Event;

/**
 * @implements \IteratorAggregate<\AwdEs\Event\EntityEvent>
 */
final readonly class EventStream implements \IteratorAggregate, \Countable
{
    /**
     * @var \SplObjectStorage<EntityEvent, null>
     */
    private \SplObjectStorage $events;

    /**
     * @param array<EntityEvent> $events
     */
    public function __construct(array $events = [])
    {
        $this->events = new \SplObjectStorage();

        foreach ($events as $event) {
            $this->events->attach($event);
        }
    }

    public function append(EntityEvent $event): void
    {
        $this->events->attach($event);
    }

    public function isEmpty(): bool
    {
        return 0 === \count($this->events);
    }

    public function emit(): \Generator
    {
        foreach ($this as $event) {
            yield $event;
            $this->events->detach($event);
        }
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        $sortedEvents = [...$this->events];
        uasort($sortedEvents, static function(EntityEvent $a, EntityEvent $b) {
            if ($a->occurredAt()->isEqual($b->occurredAt())) {
                return 0;
            }

            return ($b->occurredAt()->isGreaterThan($a->occurredAt())) ? -1 : 1;
        });

        yield from array_values($sortedEvents);
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->events);
    }
}
