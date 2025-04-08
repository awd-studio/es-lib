<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\ValueObject;

use AwdEs\Tests\Shared\AppTestCase;
use AwdEs\ValueObject\Version;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/**
 * @coversDefaultClass \AwdEs\ValueObject\Version
 *
 * @internal
 */
final class VersionTest extends AppTestCase
{
    public function testMustInitializeWithCorrectValue(): void
    {
        $version = new Version(1);

        assertSame(1, $version->value);
    }

    public function testMustReturnNextVersionAsNewInstance(): void
    {
        $version = new Version(1);
        $nextVersion = $version->next();

        assertSame(2, $nextVersion->value);
        assertNotSame($version, $nextVersion, 'Next version must return a new instance, ensuring immutability.');
    }

    public function testMustBeEqualWhenValuesAreSame(): void
    {
        $version1 = new Version(1);
        $version2 = new Version(1);
        $version3 = new Version(2);

        assertTrue($version1->isEqual($version2));
        assertFalse($version1->isEqual($version3));
    }

    public function testMustCompareCorrectlyWhenGreaterThan(): void
    {
        $version1 = new Version(2);
        $version2 = new Version(1);

        assertTrue($version1->isGreaterThan($version2));
        assertFalse($version2->isGreaterThan($version1));
    }

    public function testMustCompareCorrectlyWhenLessThan(): void
    {
        $version1 = new Version(1);
        $version2 = new Version(2);

        assertTrue($version1->isLessThan($version2));
        assertFalse($version2->isLessThan($version1));
    }
}
