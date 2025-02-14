<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reflection\Reading;

use AwdEs\Attribute\AwdEsAttribute;
use AwdEs\Attribute\Reading\AwdEsClassAttributeReader;
use AwdEs\Attribute\Reading\Exception\AwdEsClassAttributeNotFound;

final readonly class ReflectionAwdEsClassAttributeReader implements AwdEsClassAttributeReader
{
    #[\Override]
    public function read(string $attribute, string $fromClass): AwdEsAttribute
    {
        $reflection = new \ReflectionClass($fromClass);
        $attributes = $reflection->getAttributes($attribute);

        if (0 === \count($attributes)) {
            throw new AwdEsClassAttributeNotFound($attribute, $fromClass);
        }

        return $attributes[0]->newInstance();
    }
}
