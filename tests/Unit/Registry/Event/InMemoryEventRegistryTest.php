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
 *
 * @internal
 */
#[CoversFunction('__construct')]
#[CoversFunction('register')]
final class InMemoryEventRegistryTest extends AppTestCase
{
    /**
     * @var ObjectProphecy<EventMetaReader>
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
            ->willReturn($meta)
        ;

        $this->instance->register('foo');

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegisteringSameEventMultipleTimes(): void
    {
        $meta = new EventMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta)
        ;

        $this->instance->register('foo');
        $this->instance->register('foo');

        $this->expectNotToPerformAssertions();
    }

    public function testMustReturnRegisteredEventProperly(): void
    {
        $meta = new EventMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta)
        ;

        $this->instance->register('foo');

        $actual = $this->instance->eventFqnFor('bar');

        assertSame('foo', $actual);
    }

    public function testMustThrowAnExceptionIfThereIsNoRegisteredEvent(): void
    {
        $this->expectException(UnknownEventName::class);

        $this->instance->eventFqnFor('bar');
    }

    public function testMustReturnRegisteredEventsViaIterator(): void
    {
        $meta1 = new EventMeta('event1', 'Foo\Event1', 'Foo\Event1');
        $meta2 = new EventMeta('event2', 'Bar\Event2', 'Bar\Event2');

        $this->readerProphecy
            ->read(Argument::exact('Foo\Event1'))
            ->willReturn($meta1)
        ;

        $this->readerProphecy
            ->read(Argument::exact('Bar\Event2'))
            ->willReturn($meta2)
        ;

        $this->instance->register('Foo\Event1');
        $this->instance->register('Bar\Event2');

        $expected = [
            'event1' => 'Foo\Event1',
            'event2' => 'Bar\Event2',
        ];

        $actual = iterator_to_array($this->instance);

        assertSame($expected, $actual);
    }
}
