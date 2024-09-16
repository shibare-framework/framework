<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

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
