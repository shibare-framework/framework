<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi\Internal;

/**
 * Implements of QueryJoinInterface
 */
trait JoinQueryBuilderTrait
{
    /**
     * @var list<array{type: string, join_table: string, my_key: string, relation_key: string}> $join_list
     */
    private array $join_list = [];

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function innerJoin(string $join_table, string $my_key, string $relation_key): static
    {
        $type = 'INNER';
        $this->join_list[] = \compact(
            'type',
            'join_table',
            'my_key',
            'relation_key',
        );
        return $this;
    }

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function leftOuterJoin(string $join_table, string $my_key, string $relation_key): static
    {
        $type = 'LEFT OUTER';
        $this->join_list[] = \compact(
            'type',
            'join_table',
            'my_key',
            'relation_key',
        );
        return $this;
    }

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function rightOuterJoin(string $join_table, string $my_key, string $relation_key): static
    {
        $type = 'RIGHT OUTER';
        $this->join_list[] = \compact(
            'type',
            'join_table',
            'my_key',
            'relation_key',
        );
        return $this;
    }

    /**
     * @param string $join_table
     * @param string $my_key
     * @param string $relation_key
     * @return static
     */
    public function fullOuterJoin(string $join_table, string $my_key, string $relation_key): static
    {
        $type = 'FULL OUTER';
        $this->join_list[] = \compact(
            'type',
            'join_table',
            'my_key',
            'relation_key',
        );
        return $this;
    }

    protected function buildJoin(): string
    {
        $lines = [];
        foreach ($this->join_list as $join) {
            // TODO: quote
            $lines[] = \sprintf(
                '%s `%s` ON `%s`.`%s` = `%s`.`%s`',
                $join['type'],
                $this->getBaseTableName(),
                $this->getBaseTableName(),
                $join['my_key'],
                $join['join_table'],
                $join['relation_key'],
            );
        }

        return \implode(' ', $lines);
    }

    abstract private function getBaseTableName(): string;
}
