<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;
use AwdEs\Event\EntityEvent;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;

abstract readonly class ExampleEvent implements EntityEvent
{
    public function __construct(public Id $entityId, public IDateTime $occurredAt, public Version $eventNumber) {}

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

    #[\Override]
    public function version(): Version
    {
        return $this->eventNumber;
    }
}
