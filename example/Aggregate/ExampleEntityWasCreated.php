<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Attribute\AsEntityEvent;

#[AsEntityEvent(name: 'EXAMPLE_ENTITY_WAS_CREATED', entityFqn: ExampleEntity::class)]
final readonly class ExampleEntityWasCreated extends ExampleEvent {}
