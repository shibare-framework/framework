<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

use InvalidArgumentException;

/**
 * Implements of ORDER BY
 * @phpstan-require-implements QueryOrderByInterface
 */
trait OrderByQueryBuilderTrait
{
    /** @var list<array{column: string, order: 'ASC'|'DESC'}> $order_by_list */
    private array $order_by_list = [];

    /**
     * Adds ORDER BY foo DESC, bar DESC
     * @param string|string[] $columns
     * @return static
     */
    public function orderByDesc(string|array $columns): static
    {
        $c = \is_array($columns) ? $columns : [$columns];
        $orders = [];
        foreach ($c as $column) {
            $orders[] = [$column, 'DESC'];
        }
        $this->orderBy($orders);
        return $this;
    }

    /**
     * Adds ORDER BY foo ASC, bar ASC
     * @param string|string[] $columns
     * @return static
     */
    public function orderByAsc(string|array $columns): static
    {
        $c = \is_array($columns) ? $columns : [$columns];
        $orders = [];
        foreach ($c as $column) {
            $orders[] = [$column, 'ASC'];
        }
        $this->orderBy($orders);
        return $this;
    }

    /**
     * Adds ORDER BY foo DESC, bar ASC
     * NOTICE: Combines DESC and ASC will scan full records, thus it will take slow
     * ```php
     * $builder->orderBy(['user_id', 'DESC'], ['updated_at', 'ASC']);
     * ```
     * @param list<string[]> $columns
     * @return static
     */
    public function orderBy(array $columns): static
    {
        if (\count($columns) === 0) {
            throw new InvalidArgumentException('orderBy method cannot be empty');
        }
        foreach ($columns as $column) {
            if (\count($column) !== 2 || $column[0] === '') {
                throw new InvalidArgumentException('orderBy method requires column name');
            }
            if ($column[1] !== 'ASC' && $column[1] !== 'DESC') {
                throw new InvalidArgumentException(\sprintf('orderBy method allows only ASC or DESC, got "%s"', $column[1]));
            }
            $this->order_by_list[] = [
                'column' => $column[0],
                'order' => $column[1],
            ];
        }
        return $this;
    }

    protected function buildOrderBy(): string
    {
        if (\count($this->order_by_list) === 0) {
            return '';
        }

        $lines = [];
        foreach ($this->order_by_list as $orderBy) {
            // TODO: quote
            $lines[] = \sprintf('%s %s', $this->quoteColumnName($orderBy['column']), $orderBy['order']);
        }
        return \sprintf('ORDER BY %s', \implode(', ', $lines));
    }

    protected abstract function quoteColumnName(string $column): string;
}
