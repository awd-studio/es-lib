<?php

declare(strict_types=1);

namespace AwdEs\Event;

use Awd\ValueObject\IDateTime;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;

interface EntityEvent
{
    public function entityId(): Id;

    public function occurredAt(): IDateTime;

    public function version(): Version;
}
