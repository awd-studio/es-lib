<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Criteria;

final readonly class AndCriterion implements Criterion
{
    /**
     * @var Criterion[]
     */
    public array $criteria;

    public function __construct(Criterion ...$criteria)
    {
        $this->criteria = $criteria;
    }
}
