<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

/**
 * FROM
 */
interface QueryFromInterface
{
    /**
     * Adds FROM table_name
     * @param string $table_name
     * @return static
     */
    public function from(string $table_name): static;
}
