<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Criteria;

final readonly class ByTypeAndIdListCriteria implements Criteria
{
    /**
     * @param class-string<\AwdEs\Entity\AggregateEntity> $entityType
     * @param array<\AwdEs\ValueObject\Id>                $entityIdList
     */
    public function __construct(
        public string $entityType,
        public array $entityIdList,
    ) {}
}
