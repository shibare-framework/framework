<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Internal;

use InvalidArgumentException;

/**
 * Implements of QueryWhereInterface
 * @phpstan-require-implements QueryWhereInterface
 */
trait WhereQueryBuilderTrait
{
    /**
     * @var list<array{
     *   sql: string,
     *   bindings: list<int|float|string>,
     *   connection: 'AND'|'OR'
     * }> $where_list
     */
    private array $where_list = [];

    /**
     * AND WHERE `a` operator ?
     * @param string $column
     * @param string $operator
     * @param int|float|string $value
     * @return static
     */
    public function where(string $column, string $operator, int|float|string $value): static
    {
        if (!\in_array($operator, ['=', '>', '<', '>=', '<=', '<>'], true)) {
            throw new InvalidArgumentException('where operator is invalid operator=' . $operator);
        }
        $this->where_list[] = [
            'sql' => \sprintf('`%s` %s ?', $column, $operator),
            'bindings' => [$value],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` = ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function whereEquals(string $column, int|float|string $value): static
    {
        return $this->where($column, '=', $value);
    }

    /**
     * AND WHERE `a` <> ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function whereNotEquals(string $column, int|float|string $value): static
    {
        return $this->where($column, '<>', $value);
    }

    /**
     * AND WHERE `a` IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function whereIn(string $column, array $values): static
    {
        if (count($values) === 0) {
            throw new InvalidArgumentException('values of whereIn query must not be empty');
        }
        $this->where_list[] = [
            'sql' => \sprintf('`%s` IN (%s)', $column, \rtrim(\str_repeat('?, ', \count($values)), ', ')),
            'bindings' => $values,
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` NOT IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function whereNotIn(string $column, array $values): static
    {
        if (count($values) === 0) {
            throw new InvalidArgumentException('values of whereIn query must not be empty');
        }
        $this->where_list[] = [
            'sql' => \sprintf('`%s` NOT IN (%s)', $column, \rtrim(\str_repeat('?, ', \count($values)), ', ')),
            'bindings' => $values,
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` IS NULL
     * @param string $column
     * @return static
     */
    public function whereIsNull(string $column): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` IS NULL', $column),
            'bindings' => [],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` IS NOT NULL
     * @param string $column
     * @return static
     */
    public function whereIsNotNull(string $column): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` IS NOT NULL', $column),
            'bindings' => [],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function whereBetween(string $column, int|float $from, int|float $to): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` BETWEEN ? AND ?', $column),
            'bindings' => [$from, $to],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` NOT BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function whereNotBetween(string $column, int|float $from, int|float $to): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` NOT BETWEEN ? AND ?', $column),
            'bindings' => [$from, $to],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function whereLike(string $column, string $pattern): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` LIKE ?', $column),
            'bindings' => [$pattern],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * AND WHERE `a` NOT LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function whereNotLike(string $column, string $pattern): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` NOT LIKE ?', $column),
            'bindings' => [$pattern],
            'connection' => 'AND',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` operator ?
     * @param string $column
     * @param string $operator
     * @param int|float|string $value
     * @return static
     */
    public function orWhere(string $column, string $operator, int|float|string $value): static
    {
        if (!\in_array($operator, ['=', '>', '<', '>=', '<=', '<>'], true)) {
            throw new InvalidArgumentException('where operator is invalid operator=' . $operator);
        }
        $this->where_list[] = [
            'sql' => \sprintf('`%s` %s ?', $column, $operator),
            'bindings' => [$value],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` = ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function orWhereEquals(string $column, int|float|string $value): static
    {
        return $this->orWhere($column, '=', $value);
    }

    /**
     * OR WHERE `a` <> ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function orWhereNotEquals(string $column, int|float|string $value): static
    {
        return $this->orWhere($column, '<>', $value);
    }

    /**
     * OR WHERE `a` IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function orWhereIn(string $column, array $values): static
    {
        if (count($values) === 0) {
            throw new InvalidArgumentException('values of whereIn query must not be empty');
        }
        $this->where_list[] = [
            'sql' => \sprintf('`%s` IN (%s)', $column, \rtrim(\str_repeat('?, ', \count($values)), ', ')),
            'bindings' => $values,
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` NOT IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function orWhereNotIn(string $column, array $values): static
    {
        if (count($values) === 0) {
            throw new InvalidArgumentException('values of whereIn query must not be empty');
        }
        $this->where_list[] = [
            'sql' => \sprintf('`%s` NOT IN (%s)', $column, \rtrim(\str_repeat('?, ', \count($values)), ', ')),
            'bindings' => $values,
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` IS NULL
     * @param string $column
     * @return static
     */
    public function orWhereIsNull(string $column): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` IS NULL', $column),
            'bindings' => [],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` IS NOT NULL
     * @param string $column
     * @return static
     */
    public function orWhereIsNotNull(string $column): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` IS NOT NULL', $column),
            'bindings' => [],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function orWhereBetween(string $column, int|float $from, int|float $to): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` BETWEEN ? AND ?', $column),
            'bindings' => [$from, $to],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` NOT BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function orWhereNotBetween(string $column, int|float $from, int|float $to): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` NOT BETWEEN ? AND ?', $column),
            'bindings' => [$from, $to],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function orWhereLike(string $column, string $pattern): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` LIKE ?', $column),
            'bindings' => [$pattern],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * OR WHERE `a` NOT LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function orWhereNotLike(string $column, string $pattern): static
    {
        $this->where_list[] = [
            'sql' => \sprintf('`%s` NOT LIKE ?', $column),
            'bindings' => [$pattern],
            'connection' => 'OR',
        ];
        return $this;
    }

    /**
     * Builds where query
     * @return array
     * @phpstan-return array{sql: string, bindings: list<int|float|string>}
     */
    protected function buildWhere(): array
    {
        if (\count($this->where_list) === 0) {
            return [
                'sql' => '',
                'bindings' => [],
            ];
        }

        $sql = 'WHERE ';
        $bindings = [];

        foreach ($this->where_list as $where) {
            $sql .= \sprintf(
                '%s %s',
                $where['connection'],
                $where['sql'],
            );
            $bindings = array_merge($bindings, $where['bindings']);
        }

        $sql = \ltrim('AND ', \ltrim('OR ', $sql));

        return \compact('sql', 'bindings');
    }
}
