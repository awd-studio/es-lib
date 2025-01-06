<?php

declare(strict_types=1);

namespace AwdEs\Entity\Repository;

use AwdEs\Entity\AggregateEntity;
use AwdEs\ValueObject\Id;

/**
 * @template TEntity of \AwdEs\Entity\AggregateEntity
 */
interface EntityRepository
{
    /**
     * @throws \AwdEs\Entity\Exception\EntityNotFound
     */
    public function get(Id $id): AggregateEntity;

    /**
     * @throws \AwdEs\Entity\Exception\EntityPersistenceException
     */
    public function store(AggregateEntity $entity): void;
}
