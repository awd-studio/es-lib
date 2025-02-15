<?php

declare(strict_types=1);

namespace AwdEs\Registry\Entity;

final class InMemoryEntityRegistry implements EntityRegistry
{
    /**
     * @param array<string, class-string<\AwdEs\Aggregate\Entity>> $registry
     */
    public function __construct(
        private array $registry = [],
    ) {}

    #[\Override]
    public function register(string $entityFqn, string $entityName): void
    {
        $this->registry[$entityName] = $entityFqn;
    }

    #[\Override]
    public function entityFqnFor(string $entityName): string
    {
        return $this->registry[$entityName] ?? throw new Exception\UnknownEntityName($entityName);
    }
}
