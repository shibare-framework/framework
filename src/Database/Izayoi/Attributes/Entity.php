<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{
    /**
     * @param non-empty-string $table Table name
     * @param null|non-empty-string $database Database name, @default null
     */
    public function __construct(
        public readonly string $table,
        protected ?string $database = null,
    ) {}

    public function getDatabase(): ?string
    {
        return $this->database;
    }
}
