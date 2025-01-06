<?php

declare(strict_types=1);

namespace AwdEs\Entity;

use AwdEs\Event\EntityEvent;
use AwdEs\Event\EventEmitter;
use AwdEs\Event\EventStream;
use AwdEs\ValueObject\Id;

abstract class AggregateEntity implements EventEmitter
{
    private readonly EventStream $_newEvents;

    final public function __construct()
    {
        $this->_newEvents = new EventStream();
    }

    protected function recordThat(EntityEvent $event): void
    {
        $this->_newEvents->append($event);
        $this->applyEvent($event);
    }

    abstract protected function applyEvent(EntityEvent $event): void;

    #[\Override]
    final public function emitEvents(): EventStream
    {
        return $this->_newEvents;
    }

    abstract public function aggregateId(): Id;
}
