<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reading;

interface AwdEsMethodsAttributeReader
{
    /**
     * @template TAttribute of \AwdEs\Attribute\AwdEsAttribute
     *
     * @param class-string<TAttribute> $attribute
     * @param class-string             $fromClass
     *
     * @return \Generator<string, TAttribute>
     */
    public function read(string $attribute, string $fromClass): \Generator;
}
