<?php

declare(strict_types=1);

namespace AwdEs\Event\Applying;

use AwdEs\Event\EntityEvent;

interface EventApplier
{
    /**
     * Applies an event to consumers.
     *
     * @return bool returns true in case the event was applied to the consumer
     *
     * @throws Exception\EventApplyingError
     */
    public function apply(EntityEvent $event, object $consumer): bool;
}
