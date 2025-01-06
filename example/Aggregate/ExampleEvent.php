<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;
use AwdEs\Attribute\AsEntityEvent;
use AwdEs\Event\EntityEvent;
use AwdEs\ValueObject\Id;

#[AsEntityEvent(eventId: 'EXAMPLE_EVENT', aggregateRoot: ExampleAggregate::class)]
abstract readonly class ExampleEvent implements EntityEvent
{
    public function __construct(public Id $entityId, public IDateTime $occurredAt) {}

    #[\Override]
    public function entityId(): Id
    {
        return $this->entityId;
    }

    #[\Override]
    public function occurredAt(): IDateTime
    {
        return $this->occurredAt;
    }
}
