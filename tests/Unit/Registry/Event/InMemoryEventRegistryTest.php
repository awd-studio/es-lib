<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Registry\Event;

use AwdEs\Meta\Event\EventMeta;
use AwdEs\Meta\Event\Reading\EventMetaReader;
use AwdEs\Registry\Event\Exception\UnknownEventName;
use AwdEs\Registry\Event\InMemoryEventRegistry;
use AwdEs\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversFunction;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Registry\Event\InMemoryEventRegistry
 */
#[CoversFunction('__construct')]
#[CoversFunction('register')]
final class InMemoryEventRegistryTest extends AppTestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy<\AwdEs\Meta\Event\Reading\EventMetaReader>
     */
    private EventMetaReader|ObjectProphecy $readerProphecy;

    #[\Override]
    protected function setUp(): void
    {
        $this->readerProphecy = $this->prophesize(EventMetaReader::class);
        $this->instance = new InMemoryEventRegistry($this->readerProphecy->reveal());
    }

    public function testMustAllowRegistering(): void
    {
        $meta = new EventMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta);

        $this->instance->register('foo');

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegisteringSameEventMultipleTimes(): void
    {
        $meta = new EventMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta);

        $this->instance->register('foo');
        $this->instance->register('foo');

        $this->expectNotToPerformAssertions();
    }

    public function testMustReturnRegisteredEventProperly(): void
    {
        $meta = new EventMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta);

        $this->instance->register('foo');

        $actual = $this->instance->eventFqnFor('bar');

        assertSame('foo', $actual);
    }

    public function testMustThrowAnExceptionIfThereIsNoRegisteredEvent(): void
    {
        $this->expectException(UnknownEventName::class);

        $this->instance->eventFqnFor('bar');
    }
}
