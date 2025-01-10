<?php

declare(strict_types=1);

namespace AwdEs\Event\Handling;

interface EventHandlerMethodResolver
{
    /**
     * @param class-string $eventConsumer
     *
     * @throws Exception\WrongEventHandlerDeclaration
     */
    public function find(string $eventConsumer): \Generator;
}
