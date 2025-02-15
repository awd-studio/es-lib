<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Registry\Event;

use AwdEs\Registry\Event\Exception\UnknownEventName;
use AwdEs\Registry\Event\InMemoryEventRegistry;
use AwdEs\Tests\Shared\AppTestCase;
use PHPUnit\Event\UnknownEventException;
use PHPUnit\Framework\Attributes\CoversFunction;

use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Registry\Event\InMemoryEventRegistry
 */
#[CoversFunction('__construct')]
#[CoversFunction('register')]
final class InMemoryEventRegistryTest extends AppTestCase
{
    public function testMustAllowInstantiatingWithoutProvidingAnyArguments(): void
    {
        new InMemoryEventRegistry();

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowInstantiatingWithEmptyArray(): void
    {
        new InMemoryEventRegistry([]);

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegistering(): void
    {
        $instance = new InMemoryEventRegistry();
        $instance->register('foo', 'bar');

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegisteringSameEventMultipleTimes(): void
    {
        $instance = new InMemoryEventRegistry();
        $instance->register('foo', 'bar');
        $instance->register('foo', 'bar');

        $this->expectNotToPerformAssertions();
    }

    public function testMustReturnRegisteredEventProperly(): void
    {
        $instance = new InMemoryEventRegistry();
        $instance->register('foo', 'bar');

        $actual = $instance->eventFqnFor('bar');

        assertSame('foo', $actual);
    }

    public function testMustThrowAnExceptionIfThereIsNoRegisteredEvent(): void
    {
        $instance = new InMemoryEventRegistry();

        $this->expectException(UnknownEventName::class);

        $instance->eventFqnFor('bar');
    }
}
