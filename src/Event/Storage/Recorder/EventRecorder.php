<?php

declare(strict_types=1);

namespace AwdEs\Event\Storage\Recorder;

use AwdEs\Event\EntityEvent;

interface EventRecorder
{
    /**
     * @throws Exception\EventPersistenceError
     */
    public function record(EntityEvent $event): void;
}
