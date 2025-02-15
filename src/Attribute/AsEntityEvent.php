<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsEntityEvent implements AwdEsAttribute
{
    /**
     * @param string                                $name      a unique ID of an event in the system
     * @param class-string<\AwdEs\Aggregate\Entity> $entityFqn the entity class' fqcn
     */
    public function __construct(
        public string $name,
        public string $entityFqn,
    ) {}
}
