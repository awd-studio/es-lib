<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reading;

use AwdEs\Attribute\AwdEsAttribute;

interface AwdEsClassAttributeReader
{
    /**
     * @template TAttribute of \AwdEs\Attribute\AwdEsAttribute
     *
     * @param class-string<TAttribute> $attribute
     * @param class-string             $fromClass
     *
     * @return TAttribute
     *
     * @throws Exception\AwdEsClassAttributeNotFound
     */
    public function read(string $attribute, string $fromClass): AwdEsAttribute;
}
