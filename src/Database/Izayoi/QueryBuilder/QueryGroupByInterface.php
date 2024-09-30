<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

/**
 * GROUP BY
 */
interface QueryGroupByInterface
{
    /**
     * Adds GROUP BY foo, bar
     * @param string|string[] $columns
     * @return static
     */
    public function groupBy(string|array $columns): static;
}
