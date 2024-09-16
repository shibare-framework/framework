<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

use InvalidArgumentException;

/**
 * Implements of ORDER BY
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
        $this->order_by_list = [];
        foreach ($c as $column) {
            $this->order_by_list[] = [
                'column' => $column,
                'order' => 'DESC',
            ];
        }
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
        $this->order_by_list = [];
        foreach ($c as $column) {
            $this->order_by_list[] = [
                'column' => $column,
                'order' => 'ASC',
            ];
        }
        return $this;
    }

    /**
     * Adds ORDER BY foo DESC, bar ASC
     * NOTICE: Combines DESC and ASC will scan full records, thus it will take slow
     * ```php
     * $builder->orderBy(['user_id', 'DESC'], ['updated_at', 'ASC']);
     * ```
     * @param list<list{string, string}> $columns
     * @return static
     */
    public function orderBy(array $columns): static
    {
        $this->order_by_list = [];
        foreach ($columns as $column) {
            if ($column[1] !== 'ASC' && $column[1] !== 'DESC') {
                throw new InvalidArgumentException('orderBy method allows only ASC or DESC, got ' . $column[1]);
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
            $lines[] = \sprintf('`%s` %s', $orderBy['column'], $orderBy['order']);
        }
        return \sprintf('ORDER BY %s', \implode(', ', $lines));
    }
}
