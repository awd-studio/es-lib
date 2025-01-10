<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Attribute\Reflection\EventHandler;

use AwdEs\Attribute\EventHandler;
use AwdEs\Attribute\Reflection\Event\Handling\AttributeEventHandlerMethodResolver;
use AwdEs\Event\Handling\Exception\WrongEventHandlerDeclaration;
use AwdEs\Tests\Shared\AppTestCase;
use AwdEs\Tests\Unit\Attribute\Reflection\EventHandler\Mocking\BaseEventStub;
use AwdEs\Tests\Unit\Attribute\Reflection\EventHandler\Mocking\EventStub;
use AwdEs\Tests\Unit\Attribute\Reflection\EventHandler\Mocking\EventStub2;
use AwdEs\Tests\Unit\Attribute\Reflection\EventHandler\Mocking\EventStubHandler;
use PHPUnit\Framework\Attributes\CoversClass;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertSame;

#[CoversClass(AttributeEventHandlerMethodResolver::class)]
final class AttributeEventHandlerMethodResolverTest extends AppTestCase
{
    private AttributeEventHandlerMethodResolver $instance;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->instance = new AttributeEventHandlerMethodResolver();
    }

    public function testFindWorksOk(): void
    {
        $result = iterator_to_array($this->instance->find(EventStubHandler::class));

        assertCount(1, $result);
        assertArrayHasKey('handle', $result);
        assertSame($result['handle'], EventStub::class);
    }

    public function testFindsTheOnlyHandlers(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handle(EventStub $event): void {}

            public function doNotHandle(): void {}

            public function doNotHandleEvenAnEventAccepts(EventStub $event): void {}
        };

        $result = iterator_to_array($this->instance->find($handler::class));

        assertCount(1, $result);

        assertArrayHasKey('handle', $result);
        assertArrayNotHasKey('doNotHandle', $result);
        assertArrayNotHasKey('doNotHandleEvenAnEventAccepts', $result);

        assertSame($result['handle'], EventStub::class);
    }

    public function testFindsHandlersWithAnyValidName(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handle(EventStub $event): void {}

            #[EventHandler(EventStub::class)]
            public function foo(EventStub $event): void {}

            #[EventHandler(EventStub::class)]
            public function UOIUsjdosjJSOj__ijfdijf9899(EventStub $event): void {}
        };

        $result = iterator_to_array($this->instance->find($handler::class));

        assertCount(3, $result);

        assertArrayHasKey('handle', $result);
        assertArrayHasKey('foo', $result);
        assertArrayHasKey('UOIUsjdosjJSOj__ijfdijf9899', $result);

        assertSame($result['handle'], EventStub::class);
        assertSame($result['foo'], EventStub::class);
        assertSame($result['UOIUsjdosjJSOj__ijfdijf9899'], EventStub::class);
    }

    public function testFindsAllHandlers(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handle(EventStub $event): void {}

            #[EventHandler(EventStub2::class)]
            public function handleAnother(EventStub2 $event): void {}

            #[EventHandler(EventStub2::class)]
            public function handleAnotherEvenForAnEventWithHandler(EventStub2 $event): void {}
        };

        $result = iterator_to_array($this->instance->find($handler::class));

        assertCount(3, $result);

        assertArrayHasKey('handle', $result);
        assertArrayHasKey('handleAnother', $result);
        assertArrayHasKey('handleAnotherEvenForAnEventWithHandler', $result);

        assertSame($result['handle'], EventStub::class);
        assertSame($result['handleAnother'], EventStub2::class);
        assertSame($result['handleAnotherEvenForAnEventWithHandler'], EventStub2::class);
    }

    public function testAssertingPrivateHandlers(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            private function aPrivateHandle(EventStub $event): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }

    /**
     * @covers ::find()
     */
    public function testAssertingProtectedHandlers(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            protected function aProtectedHandle(EventStub $event): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }

    /**
     * @covers ::find()
     */
    public function testAssertingHandlersWithoutParams(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handleWithoutParam(): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }

    public function testAssertingHandlersWithMultipleParams(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handleWithMultipleParams(EventStub $event, EventStub2 $event2): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }

    public function testAssertingHandlersWithNonTypedParam(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handleWithNonTypedParam($event): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }

    public function testAssertingHandlersWithADifferentTypeParam(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handleWithADifferentTypeParam(EventStub2 $event2): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }

    public function testMustAcceptWiderEvents(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handleParentEvent(BaseEventStub $event): void {}
        };

        $result = iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator

        assertCount(1, $result);

        assertArrayHasKey('handleParentEvent', $result);
        assertSame($result['handleParentEvent'], EventStub::class);
    }

    public function testMustAcceptPlainObjectAsADefaultEventHandler(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handleGeneralObject(object $event): void {}
        };

        $result = iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator

        assertCount(1, $result);

        assertArrayHasKey('handleGeneralObject', $result);
        assertSame($result['handleGeneralObject'], EventStub::class);
    }

    public function testMustAcceptUnionParams(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handle(EventStub|EventStub2 $event2): void {}
        };

        $result = iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator

        assertCount(1, $result);
        assertArrayHasKey('handle', $result);
        assertSame($result['handle'], EventStub::class);
    }

    public function testMustNotAcceptIntersectionParams(): void
    {
        $handler = new class {
            #[EventHandler(EventStub::class)]
            public function handle(EventStub&EventStub2 $event2): void {}
        };

        $this->expectException(WrongEventHandlerDeclaration::class);

        iterator_to_array($this->instance->find($handler::class)); // Iterator needs to execute the generator
    }
}
