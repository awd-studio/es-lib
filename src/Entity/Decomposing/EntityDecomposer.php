<?php

declare(strict_types=1);

namespace AwdEs\Entity\Decomposing;

use AwdEs\Event\EventEmitter;

interface EntityDecomposer
{
    /**
     * @throws Exception\EntityDecomposingError
     */
    public function decompose(EventEmitter $eventEmitter): void;
}
