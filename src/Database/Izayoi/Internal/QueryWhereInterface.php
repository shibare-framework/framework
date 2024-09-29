<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\Internal;

/**
 * WHERE
 */
interface QueryWhereInterface
{
    /**
     * AND WHERE `a` operator ?
     * @param string $column
     * @param string $operator
     * @param int|float|string $value
     * @return static
     */
    public function where(string $column, string $operator, int|float|string $value): static;

    /**
     * AND WHERE `a` = ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function whereEquals(string $column, int|float|string $value): static;

    /**
     * AND WHERE `a` <> ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function whereNotEquals(string $column, int|float|string $value): static;

    /**
     * AND WHERE `a` IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function whereIn(string $column, array $values): static;

    /**
     * AND WHERE `a` NOT IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function whereNotIn(string $column, array $values): static;

    /**
     * AND WHERE `a` IS NULL
     * @param string $column
     * @return static
     */
    public function whereIsNull(string $column): static;

    /**
     * AND WHERE `a` IS NOT NULL
     * @param string $column
     * @return static
     */
    public function whereIsNotNull(string $column): static;

    /**
     * AND WHERE `a` BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function whereBetween(string $column, int|float $from, int|float $to): static;

    /**
     * AND WHERE `a` NOT BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function whereNotBetween(string $column, int|float $from, int|float $to): static;

    /**
     * AND WHERE `a` LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function whereLike(string $column, string $pattern): static;

    /**
     * AND WHERE `a` NOT LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function whereNotLike(string $column, string $pattern): static;

    /**
     * OR WHERE `a` operator ?
     * @param string $column
     * @param string $operator
     * @param int|float|string $value
     * @return static
     */
    public function orWhere(string $column, string $operator, int|float|string $value): static;

    /**
     * OR WHERE `a` = ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function orWhereEquals(string $column, int|float|string $value): static;

    /**
     * OR WHERE `a` <> ?
     * @param string $column
     * @param int|float|string $value
     * @return static
     */
    public function orWhereNotEquals(string $column, int|float|string $value): static;

    /**
     * OR WHERE `a` IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function orWhereIn(string $column, array $values): static;

    /**
     * OR WHERE `a` NOT IN (?, ?, ...)
     * @param string $column
     * @param list<int|float|string> $values
     * @return static
     */
    public function orWhereNotIn(string $column, array $values): static;

    /**
     * OR WHERE `a` IS NULL
     * @param string $column
     * @return static
     */
    public function orWhereIsNull(string $column): static;

    /**
     * OR WHERE `a` IS NOT NULL
     * @param string $column
     * @return static
     */
    public function orWhereIsNotNull(string $column): static;

    /**
     * OR WHERE `a` BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function orWhereBetween(string $column, int|float $from, int|float $to): static;

    /**
     * OR WHERE `a` NOT BETWEEN ? AND ?
     * @param string $column
     * @param int|float $from
     * @param int|float $to
     * @return static
     */
    public function orWhereNotBetween(string $column, int|float $from, int|float $to): static;

    /**
     * OR WHERE `a` LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function orWhereLike(string $column, string $pattern): static;

    /**
     * OR WHERE `a` NOT LIKE ?
     * @param string $column
     * @param string $pattern
     * @return static
     */
    public function orWhereNotLike(string $column, string $pattern): static;
}
