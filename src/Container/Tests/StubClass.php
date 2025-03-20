<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

final class StubClass
{
    /**
     * @param mixed $a
     * @param float $b
     * @param int $c
     * @param array<array-key, mixed> ...$d
     */
    public function __construct(
        // @phpstan-ignore-next-line
        $a,
        // @phpstan-ignore constructor.unusedParameter
        float $b,
        // @phpstan-ignore constructor.unusedParameter
        int $c = 1,
        // @phpstan-ignore constructor.unusedParameter
        array ...$d,
    ) {}
}
