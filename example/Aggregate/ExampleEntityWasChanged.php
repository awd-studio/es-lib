<?php

declare(strict_types=1);

namespace Example\Aggregate;

use AwdEs\Attribute\AsEntityEvent;

#[AsEntityEvent(eventId: 'EXAMPLE_ENTITY_WAS_CHANGED', entityType: ExampleEntity::class)]
final readonly class ExampleEntityWasChanged extends ExampleEvent {}
