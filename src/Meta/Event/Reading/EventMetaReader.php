<?php

declare(strict_types=1);

namespace AwdEs\Meta\Event\Reading;

use AwdEs\Meta\Event\EventMeta;

interface EventMetaReader
{
    /**
     * @template TEvent of \AwdEs\Event\EntityEvent
     *
     * @param class-string<TEvent> $eventClass
     *
     * @return EventMeta<TEvent>
     *
     * @throws \AwdEs\Meta\Event\Exception\EventMetaReadingError
     */
    public function read(string $eventClass): EventMeta;
}
