<?php

declare(strict_types=1);

namespace AwdEs\Implementation\ValueObject;

use AwdEs\ValueObject\Id;

final readonly class StringId implements Id, \Stringable
{
    public function __construct(
        public string $value,
    ) {}

    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }

    #[\Override]
    public function isSame(Id $anotherId): bool
    {
        return $this->value === $anotherId->value;
    }

    #[\Override]
    public static function fromString(string $value): static
    {
        return new self($value);
    }
}
