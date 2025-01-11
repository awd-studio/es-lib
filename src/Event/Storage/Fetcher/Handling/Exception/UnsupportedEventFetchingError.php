<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Handling\Exception;

use AwdEs\Event\Storage\Fetcher\Criteria\Criteria;
use AwdEs\Event\Storage\Fetcher\Exception\EventFetchingError;

final class UnsupportedEventFetchingError extends EventFetchingError
{
    public function __construct(public readonly Criteria $criterion, int $code = 0, ?\Throwable $previous = null)
    {
        $message = \sprintf('Unsupported criterion type "%s".', $criterion::class);

        parent::__construct($message, $code, $previous);
    }
}
