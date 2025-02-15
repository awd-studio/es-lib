<?php

declare(strict_types=1);

namespace AwdEs\Registry\Entity\Exception;

use AwdEs\Exception\NotFoundException;

final class UnknownEntityName extends NotFoundException
{
    public function __construct(string $entityName, ?\Throwable $previous = null)
    {
        $message = \sprintf('Unknown entity with name: "%s".', $entityName);

        parent::__construct($message, 0, $previous);
    }
}
