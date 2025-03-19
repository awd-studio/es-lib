<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Aggregate\Entity;
use AwdEs\Aggregate\Persistence\UoW\UnitOfWork;
use AwdEs\Aggregate\Repository\EntityRepository;
use AwdEs\ValueObject\Id;

/**
 * @implements EntityRepository<\Example\Aggregate\ExampleEntity>
 */
final readonly class UoFEntityRepository implements EntityRepository
{
    public function __construct(
        private UnitOfWork $uow,
    ) {}

    #[\Override]
    public function get(Id $id): Entity
    {
        return $this->uow->get(ExampleEntity::class, $id);
    }

    #[\Override]
    public function store(Entity $entity): void
    {
        $this->uow->store($entity);
    }
}
