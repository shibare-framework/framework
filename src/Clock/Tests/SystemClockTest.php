<?php

declare(strict_types=1);

/**
 * Class ClockTest
 * @package Shibare\Clock
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Clock\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Clock\SystemClock;

#[CoversClass(SystemClock::class)]
final class SystemClockTest extends TestCase
{
    #[Test]
    public function testNow(): void
    {
        $clock = new SystemClock();
        self::assertInstanceOf(DateTimeImmutable::class, $clock->now());
    }
}
