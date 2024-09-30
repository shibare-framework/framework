<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;
use Shibare\Database\Schema\ColumnType;

/**
 * Relational database primary key attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Column
{
    /**
     * @param non-empty-string $name Column name
     * @param ColumnType $type
     * @param bool $nullable @default false
     * @param bool $auto_increment @default false
     * @param bool $unsigned @default false
     * @param mixed|null $default @default null
     * @param mixed|null $castings manually cast column value
     */
    public function __construct(
        public readonly string $name,
        public readonly ColumnType $type,
        public readonly bool $nullable = false,
        public readonly bool $auto_increment = false,
        public readonly bool $unsigned = false,
        public readonly mixed $default = null,
        public readonly mixed $castings = null,
    ) {}
}
