<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Exception;

use AwdEs\Exception\PersistenceError;

abstract class EventFetchingError extends PersistenceError {}
