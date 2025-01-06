<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher;

use AwdEs\Event\EventStream;
use AwdEs\Event\Storage\Fetcher\Criteria\Criterion;

interface EventFetcher
{
    public function fetch(Criterion $criteria): EventStream;
}
