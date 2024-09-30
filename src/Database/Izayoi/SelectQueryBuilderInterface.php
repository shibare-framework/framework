<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use Shibare\Database\Izayoi\QueryBuilder\QueryFromInterface;
use Shibare\Database\Izayoi\QueryBuilder\QueryGroupByInterface;
use Shibare\Database\Izayoi\QueryBuilder\QueryJoinInterface;
use Shibare\Database\Izayoi\QueryBuilder\QueryOrderByInterface;
use Shibare\Database\Izayoi\QueryBuilder\QuerySelectInterface;
use Shibare\Database\Izayoi\QueryBuilder\QueryWhereInterface;

/**
 * @package Shibare\Database\Izayoi
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
