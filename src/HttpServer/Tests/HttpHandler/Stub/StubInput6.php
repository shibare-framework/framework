<?php

declare(strict_types=1);

/**
 * @license MIT
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
