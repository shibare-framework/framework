<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\QueryBuilder;

/**
 * Quotable interface
 * @phpstan-require-implements QuotableInterface
 */
trait QuotableTrait
{
    public function quoteTableName(string $table_name): string
    {
        return \sprintf('`%s`', $table_name);
    }

    public function quoteColumnName(string $column): string
    {
        if (\str_contains($column, '.')) {
            $parts = \explode('.', $column);
            if (\count($parts) !== 2) {
                throw new \InvalidArgumentException(\sprintf('Column cannot contain more than one dot "%s"', $column));
            }
            return \sprintf('`%s`.`%s`', $parts[0], $parts[1]);
        }
        return \sprintf('`%s`', $column);
    }
}
