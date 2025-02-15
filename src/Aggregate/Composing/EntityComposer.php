<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Composing;

use AwdEs\Aggregate\Entity;
use AwdEs\ValueObject\Id;

interface EntityComposer
{
    /**
     * @template TEntity of Entity
     *
     * @param class-string<TEntity> $entityType
     *
     * @return TEntity
     *
     * @throws Exception\EntityComposingError
     */
    public function compose(string $entityType, Id $entityId): Entity;
}
