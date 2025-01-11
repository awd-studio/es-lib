<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Fetcher\Handling;

use AwdEs\Event\EventStream;
use AwdEs\Event\Storage\Fetcher\Criteria\Criteria;
use AwdEs\Event\Storage\Fetcher\EventFetcher;
use AwdEs\Event\Storage\Fetcher\Handling\Exception\UnsupportedEventFetchingError;

final readonly class CasesEventFetcher implements EventFetcher
{
    /**
     * @param iterable<CriteriaHandlingCase> $cases
     */
    public function __construct(
        private iterable $cases,
    ) {}

    #[\Override]
    public function fetch(Criteria $criteria): EventStream
    {
        foreach ($this->cases as $case) {
            $result = $case->handle($criteria);

            if (null !== $result) {
                return $result;
            }
        }

        throw new UnsupportedEventFetchingError($criteria);
    }
}
