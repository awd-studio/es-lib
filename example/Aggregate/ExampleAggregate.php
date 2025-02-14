<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Aggregate\AggregateRoot;
use AwdEs\Attribute\AsAggregateRoot;
use AwdEs\Event\EntityEvent;
use AwdEs\ValueObject\Id;

#[AsAggregateRoot(name: 'EXAMPLE_AGGREGATE')]
final class ExampleAggregate extends AggregateRoot
{
    public Id $id;

    #[\Override]
    public function aggregateId(): Id
    {
        return $this->id;
    }

    #[\Override]
    public function applyEvent(EntityEvent $event): void
    {
        // TODO: Implement applyEvent() method.
    }
}
