<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

/**
 * Implements of QueryGroupByInterface
 * @phpstan-require-implements QueryGroupByInterface
 */
trait GroupByQueryBuilderTrait
{
    /** @var string[] $group_by_list */
    private array $group_by_list = [];

    /**
     * Adds GROUP BY foo, bar
     * @param string|string[] $columns
     * @return static
     */
    public function groupBy(string|array $columns): static
    {
        $this->group_by_list = [];
        $c = \is_array($columns) ? $columns : [$columns];

        foreach ($c as $column) {
            // TODO: Quote
            $this->group_by_list[] = \sprintf('`%s`', $column);
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
}
