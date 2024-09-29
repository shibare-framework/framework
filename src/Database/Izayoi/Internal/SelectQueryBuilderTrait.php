<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

/**
 * Implements of QuerySelectInterface
 * @phpstan-require-implements QuerySelectInterface
 */
trait SelectQueryBuilderTrait
{
    /** @var string[] $select_list */
    private array $select_list = [];

    /**
     * Adds SELECT with escaped columns
     * @param string[] $columns
     * @return static
     */
    public function select(array $columns): static
    {
        $this->select_list = [];
        foreach ($columns as $column) {
            // TODO: quote
            $this->select_list[] = \sprintf('`%s`', $column);
        }

        return $this;
    }

    /**
     * Adds SELECT with raw columns
     * @param string[] $columns
     * @return static
     */
    public function selectRaw(array $columns): static
    {
        $this->select_list = $columns;
        return $this;
    }

    protected function buildSelect(): string
    {
        if (\count($this->select_list) === 0) {
            return 'SELECT *';
        }
        return \sprintf('SELECT %s', \implode(', ', $this->select_list));
    }
}
