<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database;

readonly class DatabaseConfig
{
    /**
     * @param string $default
     * @param array<string, mixed> $connections
     */
    public function __construct(
        public string $default,
        public array $connections,
    ) {}
}
