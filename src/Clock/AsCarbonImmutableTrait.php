<?php

declare(strict_types=1);

/**
 * Class AsCarbonImmutableTrait
 * @package Shibare\Clock
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Clock;

use Carbon\CarbonImmutable;
use RuntimeException;

/**
 * Get time as Carbon\CarbonImmutable
 */
trait AsCarbonImmutableTrait
{
    /**
     * Get current time as CarbonImmutable
     * @return CarbonImmutable
     * @throws RuntimeException when Carbon is not installed
     */
    public function asCarbonImmutable(): CarbonImmutable
    {
        if (!\class_exists(CarbonImmutable::class)) {
            throw new RuntimeException("You need to install Carbon with `$ composer require nesbot/carbon`"); // @codeCoverageIgnore
        }

        return new CarbonImmutable($this->now());
    }
}
