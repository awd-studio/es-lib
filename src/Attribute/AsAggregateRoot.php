<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsAggregateRoot implements AwdEsAttribute
{
    public function __construct(
        public string $name,
    ) {}
}
