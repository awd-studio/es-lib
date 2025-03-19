<?php

declare(strict_types=1);

namespace AwdEs\ValueObject;

final readonly class Version
{
    /** @param positive-int $value */
    public function __construct(public int $value) {}

    public function next(): self
    {
        return new self($this->value + 1);
    }
}
