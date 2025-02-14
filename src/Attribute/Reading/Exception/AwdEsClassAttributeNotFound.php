<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reading\Exception;

use AwdEs\Exception\NotFoundException;

final class AwdEsClassAttributeNotFound extends NotFoundException
{
    /**
     * @param class-string<\AwdEs\Attribute\AwdEsAttribute> $attribute
     * @param class-string                                  $fromClass
     */
    public function __construct(string $attribute, string $fromClass, ?\Throwable $previous = null)
    {
        $message = \sprintf('Could not found the attribute "%s" in the class "%s".', $attribute, $fromClass);

        parent::__construct($message, 0, $previous);
    }
}
