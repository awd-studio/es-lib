<?php

declare(strict_types=1);

namespace AwdEs\Registry\Entity;

use AwdEs\Meta\Entity\Reading\EntityMetaReader;

final class InMemoryEntityRegistry implements EntityRegistry
{
    /**
     * @var array<string, class-string<\AwdEs\Aggregate\Entity>>
     */
    private array $registry = [];

    public function __construct(
        private readonly EntityMetaReader $reader,
    ) {}

    #[\Override]
    public function register(string $entityFqn): void
    {
        $meta = $this->reader->read($entityFqn);
        $this->registry[$meta->name] = $entityFqn;
    }

    #[\Override]
    public function entityFqnFor(string $entityName): string
    {
        return $this->registry[$entityName] ?? throw new Exception\UnknownEntityName($entityName);
    }

    /**
     * @return \Generator<string, class-string<\AwdEs\Aggregate\Entity>>
     */
    #[\Override]
    public function getIterator(): \Generator
    {
        yield from $this->registry;
    }
}
