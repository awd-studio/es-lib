<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Attribute\AsEntityEvent;

#[AsEntityEvent(eventId: 'EXAMPLE_ENTITY_WAS_CREATED', aggregateRoot: ExampleAggregate::class)]
final readonly class ExampleEntityWasCreated extends ExampleEvent {}
