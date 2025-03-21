<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\Event\Storage\Fetcher\Handling;

use AwdEs\Event\EventStream;
use AwdEs\Event\Storage\Fetcher\Criteria\Criteria;
use AwdEs\Event\Storage\Fetcher\Handling\CasesEventFetcher;
use AwdEs\Event\Storage\Fetcher\Handling\CriteriaHandlingCase;
use AwdEs\Event\Storage\Fetcher\Handling\Exception\UnsupportedEventFetchingError;
use AwdEs\Tests\Shared\AppTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

use function PHPUnit\Framework\assertSame;

/**
 * @coversDefaultClass \AwdEs\Event\Storage\Fetcher\Handling\CasesEventFetcher
 *
 * @internal
 */
final class CasesEventFetcherTest extends AppTestCase
{
    public function testMustAcceptIterableListOfHandlingCases(): void
    {
        $cases = [$this->prophesizeCase()->reveal()];
        new CasesEventFetcher($cases);

        $this->expectNotToPerformAssertions();
    }

    public function testMustAcceptAnEmptyIterableListOfHandlingCases(): void
    {
        new CasesEventFetcher([]);

        $this->expectNotToPerformAssertions();
    }

    public function testMustThrowAnExceptionIfThereAreNoCasesToHandleACriterion(): void
    {
        $criteria = $this->prophesizeCriteria()->reveal();
        $instance = new CasesEventFetcher([]);

        $this->expectException(UnsupportedEventFetchingError::class);

        $instance->fetch($criteria);
    }

    public function testMustThrowAnExceptionIfNoneOfCasesReturnsAnEventStream(): void
    {
        $criteria = $this->prophesizeCriteria()->reveal();

        $caseProphecy1 = $this->prophesizeCase();
        $caseProphecy2 = $this->prophesizeCase();

        $caseProphecy1->handle(Argument::exact($criteria))
            ->willReturn(null)
        ;
        $caseProphecy2->handle(Argument::exact($criteria))
            ->willReturn(null)
        ;

        $cases = [$caseProphecy1->reveal(), $caseProphecy2->reveal()];

        $instance = new CasesEventFetcher($cases);

        $this->expectException(UnsupportedEventFetchingError::class);

        $instance->fetch($criteria);
    }

    public function testMustReturnAnEventStreamResolvedFromACase(): void
    {
        $criteria = $this->prophesizeCriteria()->reveal();
        $eventStream = $this->prophesizeEventStream()->reveal();

        $caseProphecy = $this->prophesizeCase();
        $caseProphecy->handle(Argument::exact($criteria))
            ->willReturn($eventStream)
        ;

        $cases = [$caseProphecy->reveal()];

        $instance = new CasesEventFetcher($cases);

        $result = $instance->fetch($criteria);

        assertSame($eventStream, $result);
    }

    public function testMustCallHandlingEachCaseOnlyOnce(): void
    {
        $criteria = $this->prophesizeCriteria()->reveal();

        $caseProphecy1 = $this->prophesizeCase();
        $caseProphecy1->handle(Argument::exact($criteria))
            ->shouldBeCalledOnce()
            ->willReturn(null)
        ;

        $caseProphecy2 = $this->prophesizeCase();
        $caseProphecy2->handle(Argument::exact($criteria))
            ->shouldBeCalledOnce()
            ->willReturn(null)
        ;

        $cases = [$caseProphecy1->reveal(), $caseProphecy2->reveal()];

        $instance = new CasesEventFetcher($cases);

        $this->expectException(UnsupportedEventFetchingError::class);
        $instance->fetch($criteria);
    }

    public function testMustNotCallHandlingNextCasesIfOneHandledTheCriteria(): void
    {
        $criteria = $this->prophesizeCriteria()->reveal();
        $eventStream = $this->prophesizeEventStream()->reveal();

        $caseProphecy1 = $this->prophesizeCase();
        $caseProphecy1->handle(Argument::exact($criteria))
            ->willReturn($eventStream)
        ;

        $caseProphecy2 = $this->prophesizeCase();
        $caseProphecy2->handle(Argument::exact($criteria))
            ->shouldNotBeCalled()
        ;

        $cases = [$caseProphecy1->reveal(), $caseProphecy2->reveal()];

        $instance = new CasesEventFetcher($cases);

        $instance->fetch($criteria);
    }

    /**
     * @return ObjectProphecy<CriteriaHandlingCase>
     */
    private function prophesizeCase(): CriteriaHandlingCase|ObjectProphecy
    {
        return $this->prophesize(CriteriaHandlingCase::class);
    }

    /**
     * @return ObjectProphecy<Criteria>
     */
    private function prophesizeCriteria(): Criteria|ObjectProphecy
    {
        return $this->prophesize(Criteria::class);
    }

    /**
     * @return ObjectProphecy<EventStream>
     */
    private function prophesizeEventStream(): EventStream|ObjectProphecy
    {
        return $this->prophesize(EventStream::class);
    }
}
