<?php

declare(strict_types=1);

namespace AwdEs\Meta\Entity;

use AwdEs\Meta\ClassMeta;

/**
 * @template TEntity of \AwdEs\Entity\AggregateEntity
 *
 * @implements \AwdEs\Meta\ClassMeta<TEntity>
 */
final readonly class EntityMeta implements ClassMeta
{
    /**
     * @param class-string<TEntity>                        $fqn
     * @param class-string<\AwdEs\Aggregate\AggregateRoot> $aggregateRootFqn
     */
    public function __construct(
        public string $name,
        public string $fqn,
        public string $aggregateRootFqn,
    ) {}
}
