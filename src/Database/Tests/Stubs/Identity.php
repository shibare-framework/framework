<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Stubs;

/**
 * @template T of object
 * @package Shibare\Database\Tests\Stubs
 */
class Identity
{
    public function __construct(
        public readonly string $id,
    ) {}
}
