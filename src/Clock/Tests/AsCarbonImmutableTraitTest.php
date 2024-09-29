<?php

declare(strict_types=1);

/**
 * Class AsCarbonImmutableTraitTest
 * @package Shibare\Clock
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Clock\Tests;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Shibare\Clock\AsCarbonImmutableTrait;

#[CoversTrait(AsCarbonImmutableTrait::class)]
final class AsCarbonImmutableTraitTest extends TestCase
{
    #[Test]
    public function testAsCarbonImmutable(): void
    {
        $clock = new class implements ClockInterface {
            use AsCarbonImmutableTrait;

            public function now(): DateTimeImmutable
            {
                return new DateTimeImmutable("2021-01-01 00:00:00");
            }
        };
        self::assertInstanceOf(CarbonImmutable::class, $clock->asCarbonImmutable());
        self::assertSame("2021-01-01 00:00:00", $clock->asCarbonImmutable()->format("Y-m-d H:i:s"));
    }
}
