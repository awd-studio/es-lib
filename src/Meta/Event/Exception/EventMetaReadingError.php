<?php

declare(strict_types=1);

namespace AwdEs\Meta\Event\Exception;

use AwdEs\Event\EntityEvent;
use AwdEs\Exception\InvalidArgumentException;

abstract class EventMetaReadingError extends InvalidArgumentException
{
    public function __construct(public EntityEvent $event, int $code = 0, ?\Throwable $previous = null)
    {
        $message = \sprintf('Could not resolve metadata for an event "%s" with entity ID "%s".', $event::class, $this->event->entityId());

        parent::__construct($message, $code, $previous);
    }
}
