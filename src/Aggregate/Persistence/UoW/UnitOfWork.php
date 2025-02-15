<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Persistence\UoW;

use AwdEs\Aggregate\Entity;
use AwdEs\ValueObject\Id;

interface UnitOfWork
{
    /**
     * @template TEntity of \AwdEs\Aggregate\Entity
     *
     * @param class-string<TEntity> $entityType
     *
     * @throws \AwdEs\Aggregate\Exception\EntityNotFound
     */
    public function get(string $entityType, Id $entityId): Entity;

    /**
     * @template TEntity of \AwdEs\Aggregate\Entity
     *
     * @param TEntity $entity
     *
     * @throws \AwdEs\Aggregate\Exception\EntityPersistenceException<TEntity>
     */
    public function store(Entity $entity): void;
}
