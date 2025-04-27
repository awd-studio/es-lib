<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Aggregate\Persistence\UoW\UnitOfWork;
use AwdEs\ValueObject\Id;

final readonly class UoFEntityRepository implements IExampleEntityRepository
{
    public function __construct(
        private UnitOfWork $uow,
    ) {}

    #[\Override]
    public function get(Id $id): IExampleEntity
    {
        return $this->uow->get(ExampleEntity::class, $id);
    }

    #[\Override]
    public function store(IExampleEntity $entity): void
    {
        if (!$entity instanceof ExampleEntity) {
            throw new \InvalidArgumentException('Entity must be an instance of ExampleEntity');
        }

        $this->uow->store($entity);
    }
}
