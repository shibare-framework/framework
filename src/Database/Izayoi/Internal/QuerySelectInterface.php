<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

/**
 * SELECT
 */
interface QuerySelectInterface
{
    /**
     * Adds SELECT with escaped columns
     * @param string[] $columns
     * @return static
     */
    public function select(array $columns): static;

    /**
     * Adds SELECT with raw columns
     * NOTICE: It does not quote string, so take take of injection!
     * @param string[] $columns
     * @return static
     */
    public function selectRaw(array $columns): static;
}
