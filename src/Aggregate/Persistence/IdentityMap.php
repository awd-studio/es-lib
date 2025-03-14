<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Persistence;

use AwdEs\Aggregate\Entity;
use AwdEs\ValueObject\Id;

final class IdentityMap
{
    /** @var \WeakMap<Entity, bool> */
    private \WeakMap $map;

    public function __construct()
    {
        $this->map = new \WeakMap();
    }

    /**
     * @template TEntity of \AwdEs\Aggregate\Entity
     *
     * @param TEntity $entity
     *
     * @return TEntity
     */
    public function put(Entity $entity): Entity
    {
        if (isset($this->map[$entity])) {
            return $entity;
        }

        $this->map[$entity] = false;

        return $entity;
    }

    /**
     * @template TEntity of \AwdEs\Aggregate\Entity
     *
     * @param class-string<TEntity> $entityType
     *
     * @return TEntity|null
     */
    public function find(string $entityType, Id $id): ?Entity
    {
        foreach ($this->map as $entity => $isChanged) {
            if ($entity instanceof $entityType && $id->isSame($entity->aggregateId())) {
                return $entity;
            }
        }

        return null;
    }
}
