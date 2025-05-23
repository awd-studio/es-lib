<?php

declare(strict_types=1);

namespace Example\Engine;

use AwdEs\Event\EntityEvent;
use AwdEs\Event\EventStream;
use AwdEs\Event\InMemoryEventCollection;
use AwdEs\Meta\Event\Reading\EventMetaReader;
use AwdEs\ValueObject\Id;

final class InMemoryEventStorage
{
    /**
     * @var array<class-string<\AwdEs\Aggregate\Entity>, array<string, list<EntityEvent>>>
     */
    private array $events = [];

    public function __construct(
        private readonly EventMetaReader $eventMetaReader,
    ) {}

    public function put(EntityEvent $event): void
    {
        $eventMeta = $this->eventMetaReader->read($event::class);

        $this->events[$eventMeta->entityFqn][(string) $event->entityId()][] = $event;
    }

    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $entityType
     */
    public function getOneFor(string $entityType, Id $entityId): EventStream
    {
        if (false === \array_key_exists($entityType, $this->events)) {
            return new EventStream();
        }

        $events = $this->events[$entityType][(string) $entityId] ?? [];
        $collection = new InMemoryEventCollection($events);

        return new EventStream($collection);
    }

    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $entityType
     * @param array<Id>                             $entityIdList
     */
    public function getMultipleFor(string $entityType, array $entityIdList): EventStream
    {
        if (false === \array_key_exists($entityType, $this->events)) {
            return new EventStream();
        }

        $collection = new InMemoryEventCollection();
        foreach ($entityIdList as $entityId) {
            $events = $this->events[$entityType][(string) $entityId] ?? [];
            $collection->attach(...$events);
        }

        return new EventStream($collection);
    }

    public function drop(): void
    {
        $this->events = [];
    }
}
