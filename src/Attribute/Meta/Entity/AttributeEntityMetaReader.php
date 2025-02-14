<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Meta\Entity;

use AwdEs\Attribute\AsEntity;
use AwdEs\Attribute\Reading\AwdEsClassAttributeReader;
use AwdEs\Meta\Entity\EntityMeta;
use AwdEs\Meta\Entity\Reading\EntityMetaReader;

final readonly class AttributeEntityMetaReader implements EntityMetaReader
{
    public function __construct(
        private AwdEsClassAttributeReader $reader,
    ) {}

    #[\Override]
    public function read(string $entityClass): EntityMeta
    {
        $attr = $this->reader->read(AsEntity::class, $entityClass);

        return new EntityMeta($attr->name, $entityClass, $attr->rootFqn);
    }
}
