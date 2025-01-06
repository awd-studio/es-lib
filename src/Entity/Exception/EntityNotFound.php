<?php

declare(strict_types=1);

namespace AwdEs\Entity\Exception;

use AwdEs\Exception\NotFoundException;
use AwdEs\ValueObject\Id;

/**
 * @template TEntity of \AwdEs\Entity\AggregateEntity
 */
final class EntityNotFound extends NotFoundException
{
    /**
     * @param class-string<TEntity> $entityType
     */
    public function __construct(
        public readonly string $entityType,
        public readonly Id $entityId,
        ?\Throwable $previous = null,
    ) {
        $message = \sprintf('Entity "%s" with id "%s" not found.', $entityType, $entityId);

        parent::__construct($message, 404, $previous);
    }
}
