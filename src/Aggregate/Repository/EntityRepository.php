<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Repository;

use AwdEs\Aggregate\Entity;
use AwdEs\ValueObject\Id;

/**
 * @template TEntity of \AwdEs\Aggregate\Entity
 */
interface EntityRepository
{
    /**
     * @throws \AwdEs\Aggregate\Exception\EntityNotFound
     */
    public function get(Id $id): Entity;

    /**
     * @throws \AwdEs\Aggregate\Exception\EntityPersistenceException
     */
    public function store(Entity $entity): void;
}
