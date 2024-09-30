<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database;

/**
 * @package Shibare\Database
 */
interface InsertQueryBuilderInterface extends QueryBuilderInterface
{
    /**
     * INTO table_name
     * @param string $table_name
     * @return static
     */
    public function into(string $table_name): static;

    /**
     * Value one record
     * @param array<array-key, mixed> $record
     * @return static
     */
    public function value(array $record): static;

    /**
     * Values multiple records
     * @param array<array-key, mixed> $records
     * @return static
     */
    public function valueList(array $records): static;
}
