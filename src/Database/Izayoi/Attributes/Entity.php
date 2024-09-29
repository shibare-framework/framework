<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Entity
{
    public function __construct(
        public readonly string $table,
    ) {
        \assert(strlen($this->table) > 0, '$table cannot be empty');
    }
}
