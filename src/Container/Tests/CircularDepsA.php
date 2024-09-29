<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

final class CircularDepsA
{
    // @phpstan-ignore constructor.unusedParameter
    public function __construct(
        CircularDepsB $b,
    ) {}
}
