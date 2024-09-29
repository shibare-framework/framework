<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

final class StubClass
{
    // @phpstan-ignore-next-line
    public function __construct(
        $a,
        float $b,
        int $c = 1,
        ...$d,
    ) {}
}
