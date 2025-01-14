<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;
use AwdEs\Attribute\AsAggregateEntity;
use AwdEs\Attribute\EventHandler;
use AwdEs\Entity\AggregateEntity;
use AwdEs\Event\EntityEvent;
use AwdEs\ValueObject\Id;

#[AsAggregateEntity(entityId: 'EXAMPLE_ENTITY', aggregateRoot: ExampleAggregate::class)]
final class ExampleEntity extends AggregateEntity
{
    public Id $id;
    public IDateTime $modifiedAt;

    public static function create(Id $id, IDateTime $createdAt): self
    {
        $self = new self();
        $self->recordThat(new ExampleEntityWasCreated($id, $createdAt));

        return $self;
    }

    #[EventHandler(ExampleEntityWasCreated::class)]
    public function onExampleEntityWasCreated(ExampleEntityWasCreated $event): void
    {
        if (false === isset($this->id)) {
            throw new ExampleEntityInitViolation(sprintf('The entity "%s:%s" has already been created.', self::class, $this->id));
        }

        $this->id = $event->entityId();
        $this->modifiedAt = $event->occurredAt();
    }

    public function change(IDateTime $modifiedAt): void
    {
        $this->recordThat(new ExampleEntityWasChanged($this->id, $modifiedAt));
    }

    #[EventHandler(ExampleEntityWasChanged::class)]
    public function onExampleEntityWasChanged(ExampleEntityWasChanged $event): void
    {
        $this->modifiedAt = $event->occurredAt();
    }

    #[\Override]
    protected function applyEvent(EntityEvent $event): void
    {
        if (false === $this->id->isSame($event->entityId())) {
            return;
        }

        match ($event::class) {
            ExampleEntityWasCreated::class => $this->onExampleEntityWasCreated($event),
            default => null,
        };
    }

    #[\Override]
    public function aggregateId(): Id
    {
        return $this->id;
    }
}
