<?php

declare(strict_types=1);

namespace AwdEs\Entity\Persistence\UoW;

use AwdEs\Entity\AggregateEntity;
use AwdEs\ValueObject\Id;

interface UnitOfWork
{
    /**
     * @template TEntity of \AwdEs\Entity\AggregateEntity
     *
     * @param class-string<TEntity> $entityType
     *
     * @throws \AwdEs\Entity\Exception\EntityNotFound
     */
    public function get(string $entityType, Id $entityId): AggregateEntity;

    /**
     * @template TEntity of \AwdEs\Entity\AggregateEntity
     *
     * @param TEntity $entity
     *
     * @throws \AwdEs\Entity\Exception\EntityPersistenceException<TEntity>
     */
    public function store(AggregateEntity $entity): void;
}
