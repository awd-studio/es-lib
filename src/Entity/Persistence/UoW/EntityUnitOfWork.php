<?php

declare(strict_types=1);

namespace AwdEs\Entity\Persistence\UoW;

use AwdEs\Entity\AggregateEntity;
use AwdEs\Entity\Composing\EntityComposer;
use AwdEs\Entity\Composing\Exception\EntityComposingError;
use AwdEs\Entity\Decomposing\EntityDecomposer;
use AwdEs\Entity\Decomposing\Exception\EntityDecomposingError;
use AwdEs\Entity\Exception\EntityNotFound;
use AwdEs\Event\Storage\Recorder\Exception\EventPersistenceError;
use AwdEs\ValueObject\Id;

final readonly class EntityUnitOfWork implements UnitOfWork
{
    public function __construct(
        private EntityComposer $composer,
        private EntityDecomposer $decomposer,
    ) {}

    #[\Override]
    public function get(string $entityType, Id $entityId): AggregateEntity
    {
        try {
            return $this->composer->compose($entityType, $entityId);
        } catch (EntityComposingError $e) {
            throw new EntityNotFound($entityType, $entityId, $e);
        }
    }

    #[\Override]
    public function store(AggregateEntity $entity): void
    {
        try {
            $this->decomposer->decompose($entity);
        } catch (EntityDecomposingError $e) {
            throw new EventPersistenceError(\sprintf('Could not store an entity "%s" with ID "%s": %s', $entity::class, $entity->aggregateId(), $e->getMessage()), $e->getCode(), $e);
        }
    }
}
