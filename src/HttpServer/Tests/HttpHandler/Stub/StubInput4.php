<?php

declare(strict_types=1);

/**
 * @license MIT
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
