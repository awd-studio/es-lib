<?php

declare(strict_types=1);

namespace AwdEs\Attribute;

interface MethodsAttributeFinder
{
    /**
     * @template TAttribute of object
     *
     * @param class-string             $class
     * @param class-string<TAttribute> $attribute
     *
     * @return \Generator<string, TAttribute>
     *
     * @throws MethodsAttributeFinderError
     */
    public function find(string $class, string $attribute): \Generator;
}
