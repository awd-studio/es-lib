<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Criteria;

use AwdEs\ValueObject\Id;

final readonly class ByIdCriterion implements Criterion
{
    public function __construct(public Id $id) {}
}
