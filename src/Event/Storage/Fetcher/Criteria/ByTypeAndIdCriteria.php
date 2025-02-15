<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Criteria;

use AwdEs\ValueObject\Id;

final readonly class ByTypeAndIdCriteria implements Criteria
{
    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $entityType
     */
    public function __construct(
        public string $entityType,
        public Id $entityId,
    ) {}
}
