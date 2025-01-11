<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Handling;

use AwdEs\Event\EventStream;
use AwdEs\Event\Storage\Fetcher\Criteria\Criteria;

interface CriteriaHandlingCase
{
    public function handle(Criteria $criterion): ?EventStream;
}
