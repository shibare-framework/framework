<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use Shibare\Database\Izayoi\QueryBuilder\QuotableInterface;

/**
 * @package Shibare\Database\Izayoi
 */
interface QueryBuilderInterface extends QuotableInterface
{
    /**
     * @return array{sql: string, bindings: list<scalar>}
     */
    public function buildRawSqlAndBindings(): array;
}
