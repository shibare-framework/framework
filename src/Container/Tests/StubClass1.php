<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use stdClass;

final class StubClass1
{
    public function __construct(
        // @phpstan-ignore constructor.unusedParameter
        stdClass $a,
    ) {}
}
