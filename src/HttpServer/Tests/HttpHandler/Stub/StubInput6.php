<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\HttpHandler\Stub;

final class StubInput6
{
    /**
     * @param \Iterator&\Countable $intersection_type
     */
    public function __construct(
        public \Iterator&\Countable $intersection_type,
    ) {}
}
