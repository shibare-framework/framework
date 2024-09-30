<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database;

use Shibare\Database\QueryBuilder\QueryFromInterface;
use Shibare\Database\QueryBuilder\QueryGroupByInterface;
use Shibare\Database\QueryBuilder\QueryJoinInterface;
use Shibare\Database\QueryBuilder\QueryOrderByInterface;
use Shibare\Database\QueryBuilder\QuerySelectInterface;
use Shibare\Database\QueryBuilder\QueryWhereInterface;

/**
 * @package Shibare\Database
 */
interface SelectQueryBuilderInterface extends
    QueryBuilderInterface,
    QueryFromInterface,
    QueryGroupByInterface,
    QueryJoinInterface,
    QueryOrderByInterface,
    QuerySelectInterface,
    QueryWhereInterface
{
    /**
     * Merge UNION with multiple builders
     * @param SelectQueryBuilderInterface|SelectQueryBuilderInterface[] $builder
     * @return array{sql: string, bindings: list<scalar>}
     */
    public function union(SelectQueryBuilderInterface|array $builder): array;
}
