<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

/**
 * Implements of QueryJoinInterface
 * @phpstan-require-implements QueryJoinInterface
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
        $type = 'INNER JOIN';
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
        $type = 'LEFT OUTER JOIN';
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
        $type = 'RIGHT OUTER JOIN';
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
        $type = 'FULL OUTER JOIN';
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
            $lines[] = \sprintf(
                '%s `%s` ON `%s`.`%s` = `%s`.`%s`',
                $join['type'],
                $join['join_table'],
                $this->getBaseTableName(),
                $join['my_key'],
                $join['join_table'],
                $join['relation_key'],
            );
        }

        return \implode(' ', $lines);
    }

    protected abstract function getBaseTableName(): string;
}
