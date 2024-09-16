<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi;

use Shibare\Database\Izayoi\Internal\QueryGroupByInterface;
use Shibare\Database\Izayoi\Internal\QueryJoinInterface;
use Shibare\Database\Izayoi\Internal\QueryOrderByInterface;
use Shibare\Database\Izayoi\Internal\QuerySelectInterface;
use Shibare\Database\Izayoi\Internal\QueryWhereInterface;

/**
 * @template TEntity of object
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
     * Finds by primary keys
     * @param int|string|list<int|string> $pks
     * @return TEntity
     * @throws EntityNotFoundException
     */
    public function find(int|string|array $pks): object;

    /**
     * Finds by primary keys and return null if not found
     * @param int|string|list<int|string> $pks
     * @return TEntity|null
     */
    public function findOrNull(int|string|array $pks): ?object;

    /**
     * Finds all items
     * @return BuildResult
     */
    public function findAll(): BuildResult;

    /**
     * Finds first item
     * @return TEntity
     * @throws EntityNotFoundException
     */
    public function first(): object;

    /**
     * Finds first item and return null if not found
     * @return TEntity|null
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
     * @return iterable<int, \stdClass>
     */
    public function executeRawQuery(string $sql, array $bindings): iterable;

    /**
     * Executes UNION with multiple builders
     * @param QueryBuilderInterface<TEntity>|QueryBuilderInterface<TEntity>[] $builder
     * @return BuildResult
     */
    public function union(QueryBuilderInterface|array $builder): BuildResult;

    /**
     * @return array{sql: string, bindings: list<int|float|string>}
     */
    public function buildRawSqlAndBindings(): array;
}
