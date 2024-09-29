<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;

/**
 * Relational database primary key attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Column
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly bool $nullable = false,
        public readonly mixed $default = null,
    ) {}
}
