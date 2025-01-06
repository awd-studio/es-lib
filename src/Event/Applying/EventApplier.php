<?php

declare(strict_types=1);

namespace AwdEs\Event\Applying;

use AwdEs\Event\EntityEvent;

interface EventApplier
{
    /**
     * Applies an event to consumers.
     *
     * @throws Exception\EventApplyingError
     */
    public function apply(EntityEvent $event, object $consumer): void;
}
