<?php

declare(strict_types=1);

namespace AwdEs\Meta\Event;

use AwdEs\Meta\ClassMeta;

/**
 * @template TEvent of \AwdEs\Event\EntityEvent
 *
 * @implements \AwdEs\Meta\ClassMeta<TEvent>
 */
final readonly class EventMeta implements ClassMeta
{
    /**
     * @param class-string<TEvent>                        $fqn
     * @param class-string<\AwdEs\Entity\AggregateEntity> $entityFqn
     */
    public function __construct(
        public string $name,
        public string $fqn,
        public string $entityFqn,
    ) {}
}
