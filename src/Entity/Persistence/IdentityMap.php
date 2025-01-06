<?php

declare(strict_types=1);

namespace AwdEs\Entity\Persistence;

use AwdEs\Entity\AggregateEntity;
use AwdEs\ValueObject\Id;

final class IdentityMap
{
    /** @var \WeakMap<AggregateEntity, bool> */
    private \WeakMap $map;

    public function __construct()
    {
        $this->map = new \WeakMap();
    }

    /**
     * @template TEntity of \AwdEs\Entity\AggregateEntity
     *
     * @param TEntity $entity
     *
     * @return TEntity
     */
    public function put(AggregateEntity $entity): AggregateEntity
    {
        if (isset($this->map[$entity])) {
            return $entity;
        }

        $this->map[$entity] = false;

        return $entity;
    }

    public function find(string $entityType, Id $id): ?AggregateEntity
    {
        foreach ($this->map as $entity => $isChanged) {
            if ($entity instanceof $entityType && $id->isSame($entity->aggregateId())) {
                return $entity;
            }
        }

        return null;
    }
}
