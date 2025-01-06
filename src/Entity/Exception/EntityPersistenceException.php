<?php

declare(strict_types=1);

namespace AwdEs\Entity\Exception;

use AwdEs\Exception\LogicException;
use AwdEs\ValueObject\Id;

/**
 * @template TEntity of \AwdEs\Entity\AggregateEntity
 */
abstract class EntityPersistenceException extends LogicException
{
    /**
     * @param class-string<TEntity> $entityType
     */
    public function __construct(
        public readonly string $entityType,
        public readonly Id $entityId,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($this->formatMessage(), $code, $previous);
    }

    abstract protected function formatMessage(): string;
}
