<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;

/**
 * Relational database primary key attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class PrimaryKey
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?bool $auto_increment = true,
    ) {}
}
