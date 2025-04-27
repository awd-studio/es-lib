<?php

declare(strict_types=1);

namespace AwdEs\Tests\Unit\ValueObject;

use AwdEs\Tests\Shared\AppTestCase;
use AwdEs\ValueObject\Version;
use PHPUnit\Framework\Attributes\DataProvider;

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

    public function testIsInitialReturnsTrueWhenValueIsZero(): void
    {
        // Arrange
        $version = new Version(0);

        // Act
        $result = $version->isInitial();

        // Assert
        $this->assertTrue($result, 'Expected Version::isInitial to return true for value 0.');
    }

    #[DataProvider('nonInitialValuesProvider')]
    public function testIsInitialReturnsFalseForNonZeroValues(int $value): void
    {
        // Arrange
        $version = new Version($value);

        // Act
        $result = $version->isInitial();

        // Assert
        $this->assertFalse($result, sprintf('Expected Version::isInitial to return false for value %d.', $value));
    }

    public static function nonInitialValuesProvider(): array
    {
        return [
            'positive integer 1' => [1],
            'larger positive integer 100' => [100],
        ];
    }
}
