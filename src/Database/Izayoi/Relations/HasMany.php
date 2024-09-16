<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Relations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class HasMany
{
    /**
     * @param class-string $target
     */
    public function __construct(
        public readonly string $target,
    ) {}
}
