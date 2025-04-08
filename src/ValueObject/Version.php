<?php

declare(strict_types=1);

namespace AwdEs\ValueObject;

final readonly class Version
{
    /** @param positive-int|0 $value */
    public function __construct(public int $value) {}

    public function next(): self
    {
        return new self($this->value + 1);
    }

    public function isEqual(self $anotherVersion): bool
    {
        return $this->value === $anotherVersion->value;
    }

    public function isGreaterThan(self $anotherVersion): bool
    {
        return $this->value > $anotherVersion->value;
    }

    public function isLessThan(self $anotherVersion): bool
    {
        return $this->value < $anotherVersion->value;
    }
}
