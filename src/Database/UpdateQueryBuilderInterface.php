<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database;

use Shibare\Database\QueryBuilder\QueryFromInterface;
use Shibare\Database\QueryBuilder\QueryWhereInterface;

/**
 * @package Shibare\Database
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
