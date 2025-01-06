<?php

declare(strict_types=1);

namespace AwdEs\Event\Entity\Assembling;

use AwdEs\Entity\AggregateEntity;
use AwdEs\Entity\Composing\EntityComposer;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\Event\Storage\Fetcher\Criteria\AndCriterion;
use AwdEs\Event\Storage\Fetcher\Criteria\ByEntityTypeCriterion;
use AwdEs\Event\Storage\Fetcher\Criteria\ByIdCriterion;
use AwdEs\Event\Storage\Fetcher\EventFetcher;
use AwdEs\ValueObject\Id;

final readonly class EventsEntityComposer implements EntityComposer
{
    public function __construct(
        private EventFetcher $eventFetcher,
        private EventApplier $eventApplier,
    ) {}

    #[\Override]
    public function compose(string $entityType, Id $entityId): AggregateEntity
    {
        $criteria = new AndCriterion(new ByIdCriterion($entityId), new ByEntityTypeCriterion($entityType));
        $eventStream = $this->eventFetcher->fetch($criteria);
        $entity = new $entityType();

        foreach ($eventStream as $event) {
            $this->eventApplier->apply($event, $entity);
        }

        return $entity;
    }
}
