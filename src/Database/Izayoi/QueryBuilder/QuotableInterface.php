<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

/**
 * Quotable interface
 */
interface QuotableInterface
{
    /**
     * Quote table name
     * @param string $table_name
     * @return string
     */
    public function quoteTableName(string $table_name): string;

    /**
     * Quote column name
     * @param string $column like a or a.b
     * @return string
     */
    public function quoteColumnName(string $column): string;
}
