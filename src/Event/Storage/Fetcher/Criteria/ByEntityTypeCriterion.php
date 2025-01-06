<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Criteria;

final readonly class ByEntityTypeCriterion implements Criterion
{
    /**
     * @param class-string<\AwdEs\Entity\AggregateEntity> $entityType
     */
    public function __construct(public string $entityType) {}
}
