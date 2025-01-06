<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Reflection\EventHandler\Mocking;

use AwdEs\Attribute\EventHandler;

final readonly class EventStubHandler
{
    #[EventHandler(EventStub::class)]
    public function handle(EventStub $event): void {}
}
