<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Event;

use Awd\ValueObject\DateTime;
use AwdEs\Event\EntityEvent;
use AwdEs\Event\EventStream;
use AwdEs\Tests\Shared\AppTestCase;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/**
 * @coversDefaultClass \AwdEs\Event\EventStream
 *
 * @internal
 */
final class EsEventStreamTest extends AppTestCase
{
    private EntityEvent $eventMock;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventMock = $this->prophesizeEvent()->reveal();
    }

    public function testMustAllowCreatingEmptyStream(): void
    {
        $this->expectNotToPerformAssertions();

        new EventStream();
    }

    public function testMustAcceptAnEvent(): void
    {
        $this->expectNotToPerformAssertions();

        $instance = new EventStream();

        $instance->append($this->eventMock);
    }

    public function testMustAllowAppendAnEventTwiceWithoutExceptions(): void
    {
        $this->expectNotToPerformAssertions();

        $instance = new EventStream();

        $instance->append($this->eventMock);
        $instance->append($this->eventMock);
    }

    public function testMustReturnTheAppendedEvent(): void
    {
        $instance = new EventStream();

        $instance->append($this->eventMock);

        assertContains($this->eventMock, $instance);
    }

    public function testMustReturnTheAppendedTwiceEventOnce(): void
    {
        $instance = new EventStream();

        $instance->append($this->eventMock);
        $instance->append($this->eventMock);

        assertCount(1, $instance);
        assertContains($this->eventMock, $instance);
    }

    public function testMustReturnTrueIfThereAreNoEvents(): void
    {
        $instance = new EventStream();

        assertTrue($instance->isEmpty());
    }

    public function testMustReturnFalseIfThereAreEvents(): void
    {
        $instance = new EventStream();

        $instance->append($this->eventMock);

        assertFalse($instance->isEmpty());
    }

    public function testMustClearMessagesAfterLooping(): void
    {
        $instance = new EventStream();

        $instance->append($this->eventMock);

        assertCount(1, $instance);

        foreach ($instance as $event) {
            // do nothing
        }

        assertCount(0, $instance);
    }

    public function testMustIterateEventsFromOlderToNewer(): void
    {
        $p1 = $this->prophesizeEvent();
        $p2 = $this->prophesizeEvent();
        $p3 = $this->prophesizeEvent();

        $p1->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:47'));
        $p2->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:48'));
        $p3->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:49'));

        $event1 = $p1->reveal();
        $event2 = $p2->reveal();
        $event3 = $p3->reveal();

        $instance = new EventStream();

        $instance->append($event1);
        $instance->append($event2);
        $instance->append($event3);

        $emittedEvents = iterator_to_array($instance);

        assertSame($emittedEvents[0], $event1);
        assertSame($emittedEvents[1], $event2);
        assertSame($emittedEvents[2], $event3);
    }

    public function testMustEmitAllAppendedEvents(): void
    {
        $p1 = $this->prophesizeEvent();
        $p2 = $this->prophesizeEvent();
        $p3 = $this->prophesizeEvent();

        $p1->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:47'));
        $p2->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:48'));
        $p3->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:49'));

        $event1 = $p1->reveal();
        $event2 = $p2->reveal();
        $event3 = $p3->reveal();

        $instance = new EventStream();

        $instance->append($event1);
        $instance->append($event3);
        $instance->append($event2);

        $emittedEvents = iterator_to_array($instance);

        assertCount(3, $emittedEvents);
        assertContains($event1, $emittedEvents);
        assertContains($event2, $emittedEvents);
        assertContains($event3, $emittedEvents);
    }

    public function testMustClearItselfAfterEmitting(): void
    {
        $p1 = $this->prophesizeEvent();
        $p2 = $this->prophesizeEvent();
        $p3 = $this->prophesizeEvent();

        $p1->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:47'));
        $p2->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:48'));
        $p3->occurredAt()->willReturn(DateTime::fromString('2025-01-04 12:57:49'));

        $event1 = $p1->reveal();
        $event2 = $p2->reveal();
        $event3 = $p3->reveal();

        $instance = new EventStream();

        $instance->append($event1);
        $instance->append($event3);
        $instance->append($event2);

        iterator_to_array($instance);

        assertCount(0, $instance);
        assertTrue($instance->isEmpty());
    }

    /**
     * @return ObjectProphecy<EntityEvent>
     */
    public function prophesizeEvent(): EntityEvent|ObjectProphecy
    {
        return $this->prophesize(EntityEvent::class);
    }
}
