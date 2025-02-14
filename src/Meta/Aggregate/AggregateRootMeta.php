<?php

declare(strict_types=1);

namespace AwdEs\Meta\Aggregate;

use AwdEs\Meta\ClassMeta;

/**
 * @template TAggregateRoot of \AwdEs\Aggregate\AggregateRoot
 *
 * @implements \AwdEs\Meta\ClassMeta<TAggregateRoot>
 */
final readonly class AggregateRootMeta implements ClassMeta
{
    /**
     * @param class-string<TAggregateRoot> $fqn
     */
    public function __construct(
        public string $name,
        public string $fqn,
    ) {}
}
