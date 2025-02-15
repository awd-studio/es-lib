<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Aggregate\AggregateRoot;
use AwdEs\Attribute\AsAggregateEntity;
use AwdEs\Aggregate\Entity;
use AwdEs\Event\EntityEvent;
use AwdEs\ValueObject\Id;

#[AsAggregateEntity(name: 'EXAMPLE_AGGREGATE', rootFqn: ExampleAggregateRoot::class)]
final class ExampleAggregateRoot extends Entity implements AggregateRoot
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
