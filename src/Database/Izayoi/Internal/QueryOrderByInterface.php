<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

/**
 * ORDER BY
 */
interface QueryOrderByInterface
{
    /**
     * Adds ORDER BY foo DESC, bar DESC
     * @param string|string[] $columns
     * @return static
     */
    public function orderByDesc(string|array $columns): static;

    /**
     * Adds ORDER BY foo ASC, bar ASC
     * @param string|string[] $columns
     * @return static
     */
    public function orderByAsc(string|array $columns): static;

    /**
     * Adds ORDER BY foo DESC, bar ASC
     * NOTICE: Combines DESC and ASC will scan full records, thus it will take slow
     * ```php
     * $builder->orderBy(['user_id', 'DESC'], ['updated_at', 'ASC']);
     * ```
     * @param list<list{string, 'ASC'|'DESC'}> $columns
     * @return static
     */
    public function orderBy(array $columns): static;
}
