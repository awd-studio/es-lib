<?php

declare(strict_types=1);

namespace AwdEs\Event\Meta;

use AwdEs\Event\EntityEvent;

interface EventMetadataResolver
{
    /**
     * @throws Exception\EventMetadataResolvingError
     */
    public function resolve(EntityEvent $event): EventMeta;
}
