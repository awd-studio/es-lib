<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reflection\Event\Applying;

use AwdEs\Attribute\EventHandler;
use AwdEs\Attribute\Reading\AwdEsMethodsAttributeReader;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\Event\Applying\Exception\EventApplyingError;
use AwdEs\Event\EntityEvent;

final readonly class AttributeEventApplier implements EventApplier
{
    public function __construct(
        private AwdEsMethodsAttributeReader $finder,
    ) {}

    #[\Override]
    public function apply(EntityEvent $event, object $consumer): bool
    {
        $hasApplied = false;

        foreach ($this->finder->read(EventHandler::class, $consumer::class) as $methodName => $attribute) {
            // Skip handlers for other events
            if ($attribute->eventType !== $event::class) {
                continue;
            }

            if (false === method_exists($consumer, $methodName)) {
                throw new EventApplyingError(\sprintf('Method "%s" does not exist in the consumer "%s"!', $methodName, $consumer::class));
            }

            try {
                /* @phpstan-ignore method.dynamicName */
                $consumer->$methodName($event);
                $hasApplied = true;
            } catch (\Throwable $e) {
                throw new EventApplyingError(\sprintf('Could not apply an event "%s" to a method "%s" of the consumer "%s": %s', $event::class, $methodName, $consumer::class, $e->getMessage()), $e->getCode(), $e);
            }
        }

        return $hasApplied;
    }
}
