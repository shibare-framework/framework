<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Indexes
{
    /**
     * @param non-empty-string[] $columns
     * @param bool $unique Unique index, @default false
     */
    public function __construct(
        public readonly array $columns,
        public readonly bool $unique = false,
    ) {
        \assert(\count($this->columns) > 0, 'Indexes must have at least one column');
    }
}
