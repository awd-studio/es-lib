<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Registry\Entity;

use AwdEs\Meta\Entity\EntityMeta;
use AwdEs\Meta\Entity\Reading\EntityMetaReader;
use AwdEs\Registry\Entity\Exception\UnknownEntityName;
use AwdEs\Registry\Entity\InMemoryEntityRegistry;
use AwdEs\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversFunction;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Registry\Entity\InMemoryEntityRegistry
 *
 * @internal
 */
#[CoversFunction('__construct')]
#[CoversFunction('register')]
final class InMemoryEntityRegistryTest extends AppTestCase
{
    /**
     * @var ObjectProphecy<EntityMetaReader>
     */
    private EntityMetaReader|ObjectProphecy $readerProphecy;

    #[\Override]
    protected function setUp(): void
    {
        $this->readerProphecy = $this->prophesize(EntityMetaReader::class);
        $this->instance = new InMemoryEntityRegistry($this->readerProphecy->reveal());
    }

    public function testMustAllowRegistering(): void
    {
        $meta = new EntityMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta)
        ;

        $this->instance->register('foo');

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegisteringSameEntityMultipleTimes(): void
    {
        $meta = new EntityMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta)
        ;

        $this->instance->register('foo', 'bar');
        $this->instance->register('foo', 'bar');

        $this->expectNotToPerformAssertions();
    }

    public function testMustReturnRegisteredEntityProperly(): void
    {
        $meta = new EntityMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta)
        ;

        $this->instance->register('foo');

        $actual = $this->instance->entityFqnFor('bar');

        assertSame('foo', $actual);
    }

    public function testMustThrowAnExceptionIfThereIsNoRegisteredEntity(): void
    {
        $this->expectException(UnknownEntityName::class);

        $this->instance->entityFqnFor('bar');
    }

    public function testMustReturnRegisteredEntitiesViaIterator(): void
    {
        $meta1 = new EntityMeta('entity1', 'Foo\Entity1', 'Foo\Entity1');
        $meta2 = new EntityMeta('entity2', 'Bar\Entity2', 'Bar\Entity2');

        $this->readerProphecy
            ->read(Argument::exact('Foo\Entity1'))
            ->willReturn($meta1)
        ;

        $this->readerProphecy
            ->read(Argument::exact('Bar\Entity2'))
            ->willReturn($meta2)
        ;

        $this->instance->register('Foo\Entity1');
        $this->instance->register('Bar\Entity2');

        $expected = [
            'entity1' => 'Foo\Entity1',
            'entity2' => 'Bar\Entity2',
        ];

        $actual = iterator_to_array($this->instance);

        assertSame($expected, $actual);
    }
}
