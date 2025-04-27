<?php

declare(strict_types=1);

namespace AwdEs\Aggregate;

use AwdEs\Event\Applying\EventApplier;
use AwdEs\Event\EntityEvent;
use AwdEs\Event\EventEmitter;
use AwdEs\Event\EventStream;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;

abstract class Entity implements EventEmitter
{
    protected Version $version;

    private EventStream $_newEvents;

    final public function __construct(private readonly EventApplier $_eventApplier)
    {
        $this->version = new Version(0);
        $this->_newEvents = new EventStream();
    }

    public static function fromEventStream(EventStream $eventStream, EventApplier $eventApplier): static
    {
        $instance = new static($eventApplier);

        foreach ($eventStream as $event) {
            if (true === $instance->isInitialized() && false === $event->entityId()->isSame($instance->aggregateId())) {
                continue;
            }

            $instance->apply($event);
        }

        return $instance;
    }

    protected function recordThat(EntityEvent $event): void
    {
        $this->_newEvents->append($event);
        $this->apply($event);
    }

    private function apply(EntityEvent $event): void
    {
        if (true === $this->_eventApplier->apply($event, $this)) {
            $this->version = $event->version();
        }
    }

    #[\Override]
    final public function emitEvents(): EventStream
    {
        $newEvents = $this->_newEvents;
        $this->_newEvents = new EventStream();

        return $newEvents;
    }

    protected function isInitialized(): bool
    {
        return false === $this->version->isInitial();
    }

    /**
     * @throws Exception\EntityPropertyIsNotInitiated
     */
    abstract public function aggregateId(): Id;
}
