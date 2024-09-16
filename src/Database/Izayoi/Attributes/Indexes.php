<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Indexes
{
    /**
     * @param non-empty-string[] $columns
     */
    public function __construct(
        public readonly array $columns,
    ) {}
}
