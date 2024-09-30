<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use Shibare\Database\Izayoi\QueryBuilder\QueryFromInterface;
use Shibare\Database\Izayoi\QueryBuilder\QueryWhereInterface;

/**
 * @package Shibare\Database\Izayoi
 */
interface UpdateQueryBuilderInterface extends
    QueryBuilderInterface,
    QueryFromInterface,
    QueryWhereInterface
{
    /**
     * set values
     * @param array<string, scalar> $values
     * @return static
     */
    public function set(array $values): static;
}
