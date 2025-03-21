<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Event\Entity\Assembling;

use AwdEs\Aggregate\Composing\Exception\EntityComposingError;
use AwdEs\Aggregate\Entity;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\Event\Entity\Assembling\EventsEntityComposer;
use AwdEs\Event\EntityEvent;
use AwdEs\Event\EventStream;
use AwdEs\Event\Storage\Fetcher\Criteria\ByTypeAndIdCriteria;
use AwdEs\Event\Storage\Fetcher\EventFetcher;
use AwdEs\Tests\Shared\AppTestCase;
use AwdEs\ValueObject\Id;
use PHPUnit\Framework\Attributes\CoversNothing;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertInstanceOf;

final class SomeEntity extends Entity
{
    #[\Override]
    public function aggregateId(): Id {}
}

/**
 * @internal
 */
#[CoversNothing]
final class EventsEntityComposerTest extends AppTestCase
{
    /** @var ObjectProphecy<EventFetcher> */
    private ObjectProphecy $eventFetcher;

    /** @var ObjectProphecy<EventApplier> */
    private ObjectProphecy $eventApplier;

    private EventsEntityComposer $composer;

    #[\Override]
    protected function setUp(): void
    {
        // Create and initialize Prophecy mocks
        $this->eventFetcher = $this->prophesize(EventFetcher::class);
        $this->eventApplier = $this->prophesize(EventApplier::class);

        // Initialize the Composer with `reveal()`ed mocks
        $this->composer = new EventsEntityComposer(
            $this->eventFetcher->reveal(),
            $this->eventApplier->reveal(),
        );
    }

    public function testComposeThrowsWhenNoEventsFound(): void
    {
        $entityIdProphecy = $this->prophesize(Id::class);
        $entityIdProphecy->__toString()->willReturn('123');

        $entityType = SomeEntity::class;
        $criteria = new ByTypeAndIdCriteria($entityType, $entityIdProphecy->reveal());

        // Configure the mock: Fetcher returns an empty stream
        $this->eventFetcher
            ->fetch($criteria)
            ->willReturn(new EventStream())
        ; // Empty iterator

        // Set up test expectations
        $this->expectException(EntityComposingError::class);

        // Call the method under test
        $this->composer->compose($entityType, $entityIdProphecy->reveal());
    }

    public function testComposeCreatesEntityFromEventStream(): void
    {
        $entityIdProphecy = $this->prophesize(Id::class);
        $entityType = SomeEntity::class;
        $criteria = new ByTypeAndIdCriteria($entityType, $entityIdProphecy->reveal());

        // Stub event stream with 2 events
        $event1 = $this->prophesize(EntityEvent::class)->reveal();
        $event2 = $this->prophesize(EntityEvent::class)->reveal();
        $eventStreamProphecy = $this->prophesize(EventStream::class);
        $eventStreamProphecy
            ->getIterator()
            ->willYield([$event1, $event2])
        ;
        $eventStreamProphecy
            ->isEmpty()
            ->willReturn(false)
            ->shouldBeCalled()
        ;

        // Mock EventFetcher to return the stubbed event stream
        $this->eventFetcher
            ->fetch($criteria)
            ->willReturn($eventStreamProphecy->reveal())
        ;

        // Mock EventApplier to apply events (verify that it applies both)
        $entity = new SomeEntity($this->eventApplier->reveal());
        $this->eventApplier
            ->apply($event1, $entity)
            ->shouldBeCalled()
        ;
        $this->eventApplier
            ->apply($event2, $entity)
            ->shouldBeCalled()
        ;

        // Call the method under test
        $actualEntity = $this->composer->compose($entityType, $entityIdProphecy->reveal());

        // Assert the entity is correctly composed and of the correct type
        assertInstanceOf($entityType, $actualEntity);
    }
}
