<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Event;

use AwdEs\Event\EntityEvent;
use AwdEs\Event\InMemoryEventCollection;
use AwdEs\Tests\Shared\AppTestCase;
use AwdEs\ValueObject\Version;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Event\InMemoryEventCollection
 *
 * @internal
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

        $p1->version()->willReturn(new Version(1));
        $p2->version()->willReturn(new Version(2));
        $p3->version()->willReturn(new Version(3));

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
     * @return ObjectProphecy<EntityEvent>
     */
    public function prophesizeEvent(): EntityEvent|ObjectProphecy
    {
        return $this->prophesize(EntityEvent::class);
    }
}
