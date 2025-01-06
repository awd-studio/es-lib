<?php

declare(strict_types=1);

namespace AwdEs\ValueObject;

interface Id extends \Stringable
{
    /**
     * @param static $anotherId
     */
    public function isSame(self $anotherId): bool;

    public static function fromString(string $value): static;
}
