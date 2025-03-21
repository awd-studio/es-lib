<?php

declare(strict_types=1);

namespace AwdEs\Event\Entity\Assembling;

use AwdEs\Aggregate\Composing\EntityComposer;
use AwdEs\Aggregate\Composing\Exception\EntityComposingError;
use AwdEs\Aggregate\Entity;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\Event\Storage\Fetcher\Criteria\ByTypeAndIdCriteria;
use AwdEs\Event\Storage\Fetcher\EventFetcher;
use AwdEs\ValueObject\Id;

final readonly class EventsEntityComposer implements EntityComposer
{
    public function __construct(
        private EventFetcher $eventFetcher,
        private EventApplier $eventApplier,
    ) {}

    #[\Override]
    public function compose(string $entityType, Id $entityId): Entity
    {
        $criteria = new ByTypeAndIdCriteria($entityType, $entityId);
        $eventStream = $this->eventFetcher->fetch($criteria);

        if ($eventStream->isEmpty()) {
            throw new EntityComposingError(\sprintf('No events found for entity of type "%s" with id "%s".', $entityType, $entityId));
        }

        $entity = new $entityType($this->eventApplier);

        foreach ($eventStream as $event) {
            $this->eventApplier->apply($event, $entity);
        }

        return $entity;
    }
}
