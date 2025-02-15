<?php

declare(strict_types=1);

namespace AwdEs\Aggregate;

use AwdEs\Event\EntityEvent;
use AwdEs\Event\EventEmitter;
use AwdEs\Event\EventStream;
use AwdEs\ValueObject\Id;

abstract class Entity implements EventEmitter
{
    private EventStream $_newEvents;

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
        $newEvents = $this->_newEvents;
        $this->_newEvents = new EventStream();

        return $newEvents;
    }

    abstract public function aggregateId(): Id;
}
