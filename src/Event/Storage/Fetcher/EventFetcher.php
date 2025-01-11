<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher;

use AwdEs\Event\EventStream;
use AwdEs\Event\Storage\Fetcher\Criteria\Criteria;

interface EventFetcher
{
    /**
     * @throws Exception\EventFetchingError
     */
    public function fetch(Criteria $criteria): EventStream;
}
