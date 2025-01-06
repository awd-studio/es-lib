<?php

declare(strict_types=1);

namespace AwdEs\Reflection\Attribute;

use AwdEs\Attribute\MethodsAttributeFinderError;

final class UndefinedClass extends MethodsAttributeFinderError
{
    /**
     * @param class-string $class
     */
    public function __construct(string $class, ?\Throwable $previous = null)
    {
        $message = \sprintf('Unknown class "%s".', $class);

        parent::__construct($message, 0, $previous);
    }
}
