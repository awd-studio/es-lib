<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;
use AwdEs\Attribute\AsAggregateEntity;
use AwdEs\Attribute\EventHandler;
use AwdEs\Aggregate\Entity;
use AwdEs\ValueObject\Id;

#[AsAggregateEntity(name: 'EXAMPLE_ENTITY', rootFqn: ExampleAggregateRoot::class)]
final class ExampleEntity extends Entity implements IExampleEntity
{
    public Id $id;
    public IDateTime $modifiedAt;

    public function initWith(Id $id, IDateTime $createdAt): void
    {
        if (true === $this->isInitialized()) {
            throw new ExampleEntityProcessingError(sprintf('The entity "%s:%s" has already been created.', self::class, $this->id));
        }

        $this->recordThat(new ExampleEntityWasCreated($id, $createdAt));
    }

    #[EventHandler(ExampleEntityWasCreated::class)]
    public function onExampleEntityWasCreated(ExampleEntityWasCreated $event): void
    {
        $this->id = $event->entityId();
        $this->modifiedAt = $event->occurredAt();
    }

    #[\Override]
    public function change(IDateTime $modifiedAt): void
    {
        $this->recordThat(new ExampleEntityWasChanged($this->id, $modifiedAt, $this->version->next()));
    }

    #[EventHandler(ExampleEntityWasChanged::class)]
    public function onExampleEntityWasChanged(ExampleEntityWasChanged $event): void
    {
        $this->modifiedAt = $event->occurredAt();
    }

    #[\Override]
    public function aggregateId(): Id
    {
        return $this->id;
    }
}
