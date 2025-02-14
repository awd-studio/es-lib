<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Meta\Event;

use AwdEs\Attribute\AsEntityEvent;
use AwdEs\Attribute\Reading\AwdEsClassAttributeReader;
use AwdEs\Meta\Event\EventMeta;
use AwdEs\Meta\Event\Reading\EventMetaReader;

final readonly class AttributeEventMetaReader implements EventMetaReader
{
    public function __construct(
        private AwdEsClassAttributeReader $reader,
    ) {}

    #[\Override]
    public function read(string $entityClass): EventMeta
    {
        $attr = $this->reader->read(AsEntityEvent::class, $entityClass);

        return new EventMeta($attr->name, $entityClass, $attr->entityFqn);
    }
}
