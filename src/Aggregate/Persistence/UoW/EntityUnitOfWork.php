<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Persistence\UoW;

use AwdEs\Aggregate\Composing\EntityComposer;
use AwdEs\Aggregate\Composing\Exception\EntityComposingError;
use AwdEs\Aggregate\Decomposing\EntityDecomposer;
use AwdEs\Aggregate\Decomposing\Exception\EntityDecomposingError;
use AwdEs\Aggregate\Entity;
use AwdEs\Aggregate\Exception\EntityNotFound;
use AwdEs\Event\Storage\Recorder\Exception\EventPersistenceError;
use AwdEs\ValueObject\Id;

final readonly class EntityUnitOfWork implements UnitOfWork
{
    public function __construct(
        private EntityComposer $composer,
        private EntityDecomposer $decomposer,
    ) {}

    #[\Override]
    public function get(string $entityType, Id $entityId): Entity
    {
        try {
            return $this->composer->compose($entityType, $entityId);
        } catch (EntityComposingError $e) {
            throw new EntityNotFound($entityType, $entityId, $e);
        }
    }

    #[\Override]
    public function store(Entity $entity): void
    {
        try {
            $this->decomposer->decompose($entity);
        } catch (EntityDecomposingError $e) {
            throw new EventPersistenceError(\sprintf('Could not store an entity "%s" with ID "%s": %s', $entity::class, $entity->aggregateId(), $e->getMessage()), $e->getCode(), $e);
        }
    }
}
