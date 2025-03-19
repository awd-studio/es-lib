<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\ValueObject\Id;

final readonly class ExampleEntityFactory
{
    public function __construct(private EventApplier $eventApplier) {}

    public function create(Id $id, IDateTime $createdAt): ExampleEntity
    {
        $instance = new ExampleEntity($this->eventApplier);
        $instance->initWith($id, $createdAt);

        return $instance;
    }
}
