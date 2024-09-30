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
#[Attribute(Attribute::TARGET_CLASS)]
class PrimaryKeys extends Indexes
{
    /**
     * @param non-empty-string[] $columns
     */
    public function __construct(
        array $columns,
    ) {
        parent::__construct($columns, unique: true);
    }
}
