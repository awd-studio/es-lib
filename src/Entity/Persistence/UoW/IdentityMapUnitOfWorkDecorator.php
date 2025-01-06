<?php

declare(strict_types=1);

namespace AwdEs\Entity\Persistence\UoW;

use AwdEs\Entity\AggregateEntity;
use AwdEs\Entity\Persistence\IdentityMap;
use AwdEs\ValueObject\Id;

final readonly class IdentityMapUnitOfWorkDecorator implements UnitOfWork
{
    public function __construct(
        private UnitOfWork $parentUoW,
        private IdentityMap $map,
    ) {}

    #[\Override]
    public function get(string $entityType, Id $entityId): AggregateEntity
    {
        return $this->map->find($entityType, $entityId) ?? $this->map->put($this->parentUoW->get($entityType, $entityId));
    }

    #[\Override]
    public function store(AggregateEntity $entity): void
    {
        $this->parentUoW->store($this->map->put($entity));
    }
}
