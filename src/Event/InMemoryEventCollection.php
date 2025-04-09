<?php

declare(strict_types=1);

namespace AwdEs\Event;

final readonly class InMemoryEventCollection implements EventCollection
{
    /**
     * @var \SplObjectStorage<EntityEvent, null>
     */
    private \SplObjectStorage $events;

    /**
     * @param array<EntityEvent> $events
     */
    public function __construct(iterable $events = [])
    {
        $this->events = new \SplObjectStorage();
        $this->attach(...$events);
    }

    #[\Override]
    public function attach(EntityEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->events->attach($event);
        }
    }

    #[\Override]
    public function detach(EntityEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->events->detach($event);
        }
    }

    #[\Override]
    public function count(): int
    {
        return $this->events->count();
    }

    #[\Override]
    public function sorted(): iterable
    {
        $sortedEvents = [...$this->events];
        uasort($sortedEvents, static function(EntityEvent $a, EntityEvent $b) {
            if ($a->version()->isEqual($b->version())) {
                return 0;
            }

            return ($b->version()->isGreaterThan($a->version())) ? -1 : 1;
        });

        return array_values($sortedEvents);
    }
}
