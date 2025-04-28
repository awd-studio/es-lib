<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Aggregate;

use AwdEs\Aggregate\Entity;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\Event\EntityEvent;
use AwdEs\Tests\Shared\AppTestCase;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Aggregate\Entity
 *
 * @internal
 */
final class AbstractEntityTest extends AppTestCase
{
    private Entity $instance;
    private EventApplier|ObjectProphecy $eventApplierProphecy;
    private Id|ObjectProphecy $idProphecy;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventApplierProphecy = $this->prophesize(EventApplier::class);
        $this->idProphecy = $this->prophesize(Id::class);
        $this->instance = $this->createEntity();
    }

    public function testMustEmitRecordedEventsOnlyOnce(): void
    {
        $event = $this->prophesize(EntityEvent::class);
        $event->version()->willReturn(new Version(1));
        $event->entityId()->willReturn($this->idProphecy->reveal());

        $this->idProphecy->isSame($this->idProphecy->reveal())->willReturn(true);

        $this->eventApplierProphecy
            ->apply($event->reveal(), $this->instance)
            ->willReturn(true)
            ->shouldBeCalledOnce()
        ;

        $this->recordEvent($event->reveal());

        $emittedEvents = $this->instance->emitEvents();
        assertCount(1, $emittedEvents);

        $events = iterator_to_array($emittedEvents);
        assertSame($event->reveal(), $events[0]);

        // Second emission must be empty
        assertCount(0, $this->instance->emitEvents());
    }

    private function createEntity(): Entity
    {
        $entity = new class($this->eventApplierProphecy->reveal()) extends Entity {
            private Id $id;

            public function setId(Id $id): void
            {
                $this->id = $id;
            }

            public function aggregateId(): Id
            {
                return $this->id ?? throw new \Exception('Id not set');
            }
        };

        $entity->setId($this->idProphecy->reveal());

        return $entity;
    }

    private function recordEvent(EntityEvent $event): void
    {
        $reflection = new \ReflectionClass($this->instance);
        $method = $reflection->getMethod('recordThat');
        $method->setAccessible(true);
        $method->invoke($this->instance, $event);
    }
}
