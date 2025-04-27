<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\ValueObject\Id;

interface IExampleEntityRepository
{
    public function get(Id $id): IExampleEntity;

    public function store(IExampleEntity $entity): void;
}
