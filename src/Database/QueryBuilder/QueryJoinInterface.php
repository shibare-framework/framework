<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\QueryBuilder;

/**
 * JOIN
 */
interface QueryJoinInterface
{
    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function innerJoin(string $join_table, string $my_key, string $relation_key): static;

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function leftOuterJoin(string $join_table, string $my_key, string $relation_key): static;

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function rightOuterJoin(string $join_table, string $my_key, string $relation_key): static;

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function fullOuterJoin(string $join_table, string $my_key, string $relation_key): static;
}
