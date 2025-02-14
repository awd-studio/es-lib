<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Meta\Aggregate;

use AwdEs\Attribute\AsAggregateRoot;
use AwdEs\Attribute\Reading\AwdEsClassAttributeReader;
use AwdEs\Meta\Aggregate\AggregateRootMeta;
use AwdEs\Meta\Aggregate\Reading\AggregateRootMetaReader;

final readonly class AttributeAggregateRootMetaReader implements AggregateRootMetaReader
{
    public function __construct(
        private AwdEsClassAttributeReader $reader,
    ) {}

    #[\Override]
    public function read(string $aggregateRootClass): AggregateRootMeta
    {
        $attr = $this->reader->read(AsAggregateRoot::class, $aggregateRootClass);

        return new AggregateRootMeta($attr->name, $aggregateRootClass);
    }
}
