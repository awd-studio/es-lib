<?php

declare(strict_types=1);

namespace AwdEs\Registry\Event\Exception;

use AwdEs\Exception\NotFoundException;

final class UnknownEventName extends NotFoundException
{
    public function __construct(string $eventName, ?\Throwable $previous = null)
    {
        $message = \sprintf('Unknown event with name: "%s".', $eventName);

        parent::__construct($message, 0, $previous);
    }
}
