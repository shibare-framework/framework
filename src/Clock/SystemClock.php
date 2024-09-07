<?php

declare(strict_types=1);

/**
 * Class Clock
 * @package Shibare\Clock
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Clock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * Represents always system time
 */
class SystemClock implements ClockInterface
{
    use AsCarbonImmutableTrait;
    use AsChronosTrait;

    /**
     * {@inheritDoc}
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable("now");
    }
}
