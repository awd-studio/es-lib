<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Exception;

use AwdEs\Exception\LogicException;

final class EntityPropertyIsNotInitiated extends LogicException
{
    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $entityType
     */
    public function __construct(string $entityType, string $propertyName, ?\Throwable $previous = null)
    {
        $message = \sprintf('The property "%s" on the entity "%s" is not initiated.', $entityType, $propertyName);

        parent::__construct($message, 0, $previous);
    }
}
