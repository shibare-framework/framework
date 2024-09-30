<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database;

use Shibare\Database\QueryBuilder\QuotableInterface;

/**
 * @package Shibare\Database
 */
interface QueryBuilderInterface extends QuotableInterface
{
    /**
     * @return array{sql: string, bindings: list<scalar>}
     */
    public function buildRawSqlAndBindings(): array;
}
