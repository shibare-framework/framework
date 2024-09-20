<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use stdClass;

final class StubClass1
{
    // @phpstan-ignore-next-line
    public function __construct(
        stdClass $a,
    ) {}
}
