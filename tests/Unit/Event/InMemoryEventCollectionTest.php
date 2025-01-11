<?php

declare(strict_types=1);

namespace AwdEs\Tests\Integration\Event;

use Awd\ValueObject\DateTime;
use AwdEs\Event\EntityEvent;
use AwdEs\Event\InMemoryEventCollection;
use AwdEs\Tests\Shared\AppTestCase;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertNotContains;
use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Event\InMemoryEventCollection
 */
final class InMemoryEventCollectionTest extends AppTestCase
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

        new InMemoryEventCollection();
    }

    public function testMustAcceptAnEvent(): void
    {
        $this->expectNotToPerformAssertions();

        $instance = new InMemoryEventCollection();

        $instance->attach($this->eventMock);
    }

    public function testMustAcceptMultipleEvents(): void
    {
        $this->expectNotToPerformAssertions();

        $instance = new InMemoryEventCollection();

        $e1 = $this->prophesizeEvent()->reveal();
        $e2 = $this->prophesizeEvent()->reveal();

        $instance->attach($e1, $e2);
    }

    public function testMustAcceptSameEventSetMultipleTimesOnlyOnce(): void
    {
        $instance = new InMemoryEventCollection();

        $instance->attach($this->eventMock, $this->eventMock, $this->eventMock);

        assertCount(1, $instance);
    }

    public function testMustAllowDetachingEvents(): void
    {
        $instance = new InMemoryEventCollection();

        $instance->attach($this->eventMock);
        $instance->detach($this->eventMock);

        assertCount(0, $instance);
    }

    public function testMustAllowDetachingMultipleEvents(): void
    {
        $instance = new InMemoryEventCollection();

        $e1 = $this->prophesizeEvent()->reveal();
        $e2 = $this->prophesizeEvent()->reveal();

        $instance->attach($e1, $e2);
        $instance->detach($e1, $e2);

        assertCount(0, $instance);
    }

    public function testMustCalculateProperlyAllAttachedEvents(): void
    {
        $instance = new InMemoryEventCollection();

        $e1 = $this->prophesizeEvent()->reveal();
        $e2 = $this->prophesizeEvent()->reveal();
        $e3 = $this->prophesizeEvent()->reveal();

        $instance->attach($e1, $e2, $e3);

        assertCount(3, $instance);
    }

    public function testMustIterateEventsFromOlderToNewerDespiteTheOrderOfAppending(): void
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

        $instance = new InMemoryEventCollection();

        $instance->attach($event1);
        $instance->attach($event3);
        $instance->attach($event2);

        $emittedEvents = iterator_to_array($instance->sorted());

        assertSame($emittedEvents[0], $event1);
        assertSame($emittedEvents[1], $event2);
        assertSame($emittedEvents[2], $event3);
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy<\AwdEs\Event\EntityEvent>
     */
    public function prophesizeEvent(): ObjectProphecy|EntityEvent
    {
        return $this->prophesize(EntityEvent::class);
    }
}
