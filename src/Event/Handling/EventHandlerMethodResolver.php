<?php

declare(strict_types=1);

namespace AwdEs\Event\Handling;

use AwdEs\Reflection\EventHandler\WrongEventHandlerDeclaration;

interface EventHandlerMethodResolver
{
    /**
     * @param class-string $eventConsumer
     *
     * @throws WrongEventHandlerDeclaration
     */
    public function find(string $eventConsumer): \Generator;
}
