<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reflection;

use AwdEs\Attribute\MethodsAttributeFinder;

final readonly class ReflectionMethodsAttributeFinder implements MethodsAttributeFinder
{
    #[\Override]
    public function find(string $class, string $attribute): \Generator
    {
        $reflection = new \ReflectionClass($class);

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
