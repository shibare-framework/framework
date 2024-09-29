<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\HttpHandler\Stub;

final class StubInput4
{
    /**
     * @param string $name
     */
    public function __construct(
        public string $name,
    ) {}
}
