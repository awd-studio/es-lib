<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Attribute\Reflection\Reading;

use AwdEs\Attribute\AwdEsAttribute;
use AwdEs\Attribute\Reading\Exception\AwdEsClassAttributeNotFound;
use AwdEs\Attribute\Reflection\Reading\ReflectionAwdEsClassAttributeReader;
use AwdEs\Tests\Shared\AppTestCase;

use function PHPUnit\Framework\assertInstanceOf;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class TestMeAttribute implements AwdEsAttribute {}

#[TestMeAttribute]
final class TestMeClass {}

/**
 * @coversDefaultClass \AwdEs\Attribute\Reflection\Reading\ReflectionAwdEsClassAttributeReader
 *
 * @internal
 */
final class ReflectionAwdEsClassAttributeReaderTest extends AppTestCase
{
    private ReflectionAwdEsClassAttributeReader $instance;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->instance = new ReflectionAwdEsClassAttributeReader();
    }

    public function testMustThrowAnExceptionIfThereIsNoAttribute(): void
    {
        $this->expectException(AwdEsClassAttributeNotFound::class);

        $attrClass = new class implements AwdEsAttribute {};
        $classWithoutAttribute = new class {};

        $this->instance->read($attrClass::class, $classWithoutAttribute::class);
    }

    public function testMustReturnTheAttribute(): void
    {
        $this->expectException(AwdEsClassAttributeNotFound::class);

        $result = $this->instance->read(TestMeClass::class, TestMeAttribute::class);

        assertInstanceOf(TestMeAttribute::class, $result);
    }
}
