<?php

declare(strict_types=1);

namespace Example\EventHandler;

use AwdEs\Attribute\EventHandler;
use Example\Aggregate\ExampleEvent;

final readonly class ExampleEventHandler
{
    #[EventHandler(ExampleEvent::class)]
    public function handler(ExampleEvent $event): void {}
}
