<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reflection\Reading;

use AwdEs\Attribute\Reading\AwdEsMethodsAttributeReader;

final readonly class ReflectionAwdEsMethodsAttributeReader implements AwdEsMethodsAttributeReader
{
    #[\Override]
    public function read(string $attribute, string $fromClass): \Generator
    {
        $reflection = new \ReflectionClass($fromClass);

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes($attribute);

            if (0 === \count($attributes)) {
                continue;
            }

            foreach ($attributes as $attributeReflection) {
                yield $method->getName() => $attributeReflection->newInstance();
            }
        }
    }
}
