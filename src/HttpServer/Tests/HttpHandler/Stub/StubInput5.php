<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\HttpHandler\Stub;

final class StubInput5
{
    /**
     * @param string|int $name_or_age
     */
    public function __construct(
        public string|int $name_or_age,
    ) {}
}
