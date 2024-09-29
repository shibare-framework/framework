<?php

declare(strict_types=1);

namespace Shibare\Clock;

use DateTimeImmutable;

/**
 * Class ClockTest
 * @package Shibare\Clock
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

if (!\function_exists('system_now')) {
    /**
     * Get current system time
     * @return DateTimeImmutable
     */
    function system_now(): DateTimeImmutable
    {
        return (new SystemClock())->now();
    }
}

if (!\function_exists('fixed_now')) {
    /**
     * Get global expected time
     * @return DateTimeImmutable
     * @throws \RuntimeException when global clock is not set
     */
    function fixed_now(): DateTimeImmutable
    {
        return FixedClock::getFixedClock()->now();
    }
}
