<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;
use AwdEs\Attribute\AsEntityEvent;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;

#[AsEntityEvent(name: 'EXAMPLE_ENTITY_WAS_CREATED', entityFqn: ExampleEntity::class)]
final readonly class ExampleEntityWasCreated extends ExampleEvent
{
    public function __construct(Id $entityId, IDateTime $occurredAt)
    {
        parent::__construct($entityId, $occurredAt, new Version(1));
    }
}
