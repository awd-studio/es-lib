<?php

declare(strict_types=1);

namespace AwdEs\Event;

interface EventEmitter
{
    public function emitEvents(): EventStream;
}
