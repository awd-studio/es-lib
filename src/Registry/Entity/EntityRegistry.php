<?php

declare(strict_types=1);

namespace AwdEs\Registry\Entity;

/**
 * @extends \IteratorAggregate<string, class-string<\AwdEs\Aggregate\Entity>>
 */
interface EntityRegistry extends \IteratorAggregate
{
    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $entityFqn
     */
    public function register(string $entityFqn): void;

    /**
     * @return class-string<\AwdEs\Aggregate\Entity>
     *
     * @throws Exception\UnknownEntityName
     */
    public function entityFqnFor(string $entityName): string;
}
