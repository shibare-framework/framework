<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database;

use Shibare\Database\QueryBuilder\QueryFromInterface;
use Shibare\Database\QueryBuilder\QueryWhereInterface;

/**
 * @package Shibare\Database\Izayoi
 */
interface DeleteQueryBuilderInterface extends
    QueryBuilderInterface,
    QueryFromInterface,
    QueryWhereInterface
{
}
