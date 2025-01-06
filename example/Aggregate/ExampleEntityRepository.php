<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Entity\AggregateEntity;
use AwdEs\Entity\Persistence\UoW\UnitOfWork;
use AwdEs\Entity\Repository\EntityRepository;
use AwdEs\ValueObject\Id;

/**
 * @implements EntityRepository<\Example\Aggregate\ExampleEntity>
 */
final readonly class ExampleEntityRepository implements EntityRepository
{
    public function __construct(
        private UnitOfWork $uow,
    ) {}

    #[\Override]
    public function get(Id $id): AggregateEntity
    {
        return $this->uow->get(ExampleEntity::class, $id);
    }

    #[\Override]
    public function store(AggregateEntity $entity): void
    {
        $this->uow->store($entity);
    }
}
