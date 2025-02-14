<?php

declare(strict_types=1);

namespace AwdEs\Meta\Aggregate\Reading;

use AwdEs\Meta\Aggregate\AggregateRootMeta;

interface AggregateRootMetaReader
{
    /**
     * @template TAggregateRoot of \AwdEs\Aggregate\AggregateRoot
     *
     * @param class-string<TAggregateRoot> $aggregateRootClass
     *
     * @return AggregateRootMeta<TAggregateRoot>
     *
     * @throws \AwdEs\Meta\Aggregate\Exception\AggregateRootMetaReadingError
     */
    public function read(string $aggregateRootClass): AggregateRootMeta;
}
