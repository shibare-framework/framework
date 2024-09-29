<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\HttpHandler\Stub;

final class StubInput3
{
    /**
     * @param ?string $extra
     * @return void
     */
    public function __construct(
        public ?string $extra,
    ) {}
}
