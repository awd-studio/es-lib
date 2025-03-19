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
        $this->_newEvents = new EventStream();
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
        return (new \ReflectionProperty($this, 'version'))->isInitialized();
    }

    /**
     * @throws Exception\EntityPropertyIsNotInitiated
     */
    abstract public function aggregateId(): Id;
}
