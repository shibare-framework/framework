<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

/**
 * Implements of QueryGroupByInterface
 * @phpstan-require-implements QueryGroupByInterface
 */
trait GroupByQueryBuilderTrait
{
    /** @var string[] $group_by_list */
    protected array $group_by_list = [];

    /**
     * Adds GROUP BY foo, bar
     * @param string|string[] $columns
     * @return static
     */
    public function groupBy(string|array $columns): static
    {
        $c = \is_array($columns) ? $columns : [$columns];

        foreach ($c as $column) {
            $this->group_by_list[] = $this->quoteColumnName($column);
        }

        return $this;
    }

    protected function buildGroupBy(): string
    {
        if (\count($this->group_by_list) === 0) {
            return '';
        }

        return \sprintf('GROUP BY %s', \implode(', ', $this->group_by_list));
    }

    public abstract function quoteColumnName(string $column): string;
}
