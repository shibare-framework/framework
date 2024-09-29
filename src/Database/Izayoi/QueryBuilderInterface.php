<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use Shibare\Database\Izayoi\Internal\QueryGroupByInterface;
use Shibare\Database\Izayoi\Internal\QueryJoinInterface;
use Shibare\Database\Izayoi\Internal\QueryOrderByInterface;
use Shibare\Database\Izayoi\Internal\QuerySelectInterface;
use Shibare\Database\Izayoi\Internal\QueryWhereInterface;

/**
 * @package Shibare\Database\Izayoi
 */
interface QueryBuilderInterface extends
    QueryGroupByInterface,
    QueryJoinInterface,
    QueryOrderByInterface,
    QuerySelectInterface,
    QueryWhereInterface
{
    /**
     * Finds first item and return null if not found
     * @return object|null
     */
    public function firstOrNull(): ?object;

    /**
     * Finds all items by query
     * @return BuildResult
     */
    public function get(): BuildResult;

    /**
     * Executes raw query and returns raw \stdClass iterable(lazily)
     * @param string $sql
     * @param array<int, int|float|string> $bindings
     * @return iterable<int, mixed>
     */
    public function executeRawQuery(string $sql, array $bindings): iterable;

    /**
     * Executes UNION with multiple builders
     * @param QueryBuilderInterface|QueryBuilderInterface[] $builder
     * @return BuildResult
     */
    public function union(QueryBuilderInterface|array $builder): BuildResult;

    /**
     * @return array{sql: string, bindings: list<int|float|string>}
     */
    public function buildRawSqlAndBindings(): array;
}
