<?php

declare(strict_types=1);

namespace AwdEs\Registry\Entity;

interface EntityRegistry
{
    /**
     * @param class-string<\AwdEs\Aggregate\Entity> $entityFqn
     */
    public function register(string $entityFqn, string $entityName): void;

    /**
     * @return class-string<\AwdEs\Aggregate\Entity>
     *
     * @throws Exception\UnknownEntityName
     */
    public function entityFqnFor(string $entityName): string;
}
