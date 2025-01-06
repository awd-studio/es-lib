<?php

declare(strict_types=1);

namespace AwdEs\Entity\Composing;

use AwdEs\Entity\AggregateEntity;
use AwdEs\ValueObject\Id;

interface EntityComposer
{
    /**
     * @template TEntity of AggregateEntity
     *
     * @param class-string<TEntity> $entityType
     *
     * @return TEntity
     *
     * @throws Exception\EntityComposingError
     */
    public function compose(string $entityType, Id $entityId): AggregateEntity;
}
