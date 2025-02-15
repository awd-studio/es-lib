<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Registry\Entity;

use AwdEs\Registry\Entity\Exception\UnknownEntityName;
use AwdEs\Registry\Entity\InMemoryEntityRegistry;
use AwdEs\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversFunction;

use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Registry\Entity\InMemoryEntityRegistry
 */
#[CoversFunction('__construct')]
#[CoversFunction('register')]
final class InMemoryEntityRegistryTest extends AppTestCase
{
    public function testMustAllowInstantiatingWithoutProvidingAnyArguments(): void
    {
        new InMemoryEntityRegistry();

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowInstantiatingWithEmptyArray(): void
    {
        new InMemoryEntityRegistry([]);

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegistering(): void
    {
        $instance = new InMemoryEntityRegistry();
        $instance->register('foo', 'bar');

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegisteringSameEntityMultipleTimes(): void
    {
        $instance = new InMemoryEntityRegistry();
        $instance->register('foo', 'bar');
        $instance->register('foo', 'bar');

        $this->expectNotToPerformAssertions();
    }

    public function testMustReturnRegisteredEntityProperly(): void
    {
        $instance = new InMemoryEntityRegistry();
        $instance->register('foo', 'bar');

        $actual = $instance->entityFqnFor('bar');

        assertSame('foo', $actual);
    }

    public function testMustThrowAnExceptionIfThereIsNoRegisteredEntity(): void
    {
        $instance = new InMemoryEntityRegistry();

        $this->expectException(UnknownEntityName::class);

        $instance->entityFqnFor('bar');
    }
}
