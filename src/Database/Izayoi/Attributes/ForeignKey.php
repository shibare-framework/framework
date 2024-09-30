<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;
use Shibare\Database\Schema\ForeignKeyReferenceOption;

/**
 * Relational database foreign key attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ForeignKey
{
    /**
     * @param non-empty-string $target Target class name
     * @param non-empty-string|non-empty-string[]|null $inner_keys Inner keys, @default null
     * @param non-empty-string|non-empty-string[]|null $outer_keys Outer keys, @default null
     * @param ForeignKeyReferenceOption|null $on_delete On delete reference option, @default null
     * @param ForeignKeyReferenceOption|null $on_update On update reference option, @default null
     */
    public function __construct(
        public readonly string $target,
        public readonly string|array|null $inner_keys = null,
        public readonly string|array|null $outer_keys = null,
        public readonly ?ForeignKeyReferenceOption $on_delete = null,
        public readonly ?ForeignKeyReferenceOption $on_update = null,
    ) {}
}
