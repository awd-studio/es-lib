<?php

declare(strict_types=1);

namespace AwdEs\Event;

use Awd\ValueObject\IDateTime;
use AwdEs\ValueObject\Id;

interface EntityEvent
{
    public function entityId(): Id;

    public function occurredAt(): IDateTime;
}
