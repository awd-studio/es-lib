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
 */
#[CoversFunction('__construct')]
#[CoversFunction('register')]
final class InMemoryEntityRegistryTest extends AppTestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy<\AwdEs\Meta\Entity\Reading\EntityMetaReader>
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
            ->willReturn($meta);

        $this->instance->register('foo');

        $this->expectNotToPerformAssertions();
    }

    public function testMustAllowRegisteringSameEntityMultipleTimes(): void
    {
        $meta = new EntityMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta);

        $this->instance->register('foo', 'bar');
        $this->instance->register('foo', 'bar');

        $this->expectNotToPerformAssertions();
    }

    public function testMustReturnRegisteredEntityProperly(): void
    {
        $meta = new EntityMeta('bar', 'foo', 'foo');
        $this->readerProphecy
            ->read(Argument::exact('foo'))
            ->willReturn($meta);

        $this->instance->register('foo');

        $actual = $this->instance->entityFqnFor('bar');

        assertSame('foo', $actual);
    }

    public function testMustThrowAnExceptionIfThereIsNoRegisteredEntity(): void
    {
        $this->expectException(UnknownEntityName::class);

        $this->instance->entityFqnFor('bar');
    }
}
