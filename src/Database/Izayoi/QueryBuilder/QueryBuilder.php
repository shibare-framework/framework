<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

use Shibare\Database\Izayoi\QueryBuilderInterface;

class QueryBuilder implements QueryBuilderInterface
{
    use GroupByQueryBuilderTrait;
    use JoinQueryBuilderTrait;
    use OrderByQueryBuilderTrait;
    use SelectQueryBuilderTrait;
    use WhereQueryBuilderTrait;

    protected ?string $table_name = null;

    public function from(string $table_name): static
    {
        $this->table_name = $table_name;

        return $this;
    }

    protected function getBaseTableName(): string
    {
        if (\is_null($this->table_name)) {
            throw new \RuntimeException('Table name is not set');
        }
        return $this->table_name;
    }

    public function union(QueryBuilderInterface|array $builder): array
    {
        $builders = \is_array($builder) ? $builder : [$builder];

        $sql_list = [];
        $bindings_list = [];
        $base = $this->buildRawSqlAndBindings();
        $sql_list[] = $base['sql'];
        $bindings_list = \array_merge($bindings_list, $base['bindings']);
        foreach ($builders as $b) {
            $sql_bindings = $b->buildRawSqlAndBindings();
            $sql_list[] = $sql_bindings['sql'];
            $bindings_list = \array_merge($bindings_list, $sql_bindings['bindings']);
        }

        return [
            'sql' => \implode(' UNION ', $sql_list),
            'bindings' => $bindings_list,
        ];
    }

    public function buildRawSqlAndBindings(): array
    {
        $select = $this->buildSelect();
        $from = $this->buildFrom();
        $join = $this->buildJoin();
        $where = $this->buildWhere();
        $groupBy = $this->buildGroupBy();
        $orderBy = $this->buildOrderBy();

        $sql = \implode(' ', \array_filter([
            $select,
            $from,
            $join,
            $where['sql'],
            $groupBy,
            $orderBy,
        ]));

        $bindings = $where['bindings'];

        return compact('sql', 'bindings');
    }

    protected function buildFrom(): string
    {
        return \sprintf('FROM %s', $this->quoteTableName($this->getBaseTableName()));
    }

    protected function quoteTableName(string $table_name): string
    {
        return \sprintf('`%s`', $table_name);
    }

    protected function quoteColumnName(string $column): string
    {
        if (\str_contains($column, '.')) {
            $parts = \explode('.', $column);
            if (\count($parts) !== 2) {
                throw new \InvalidArgumentException(\sprintf('Column cannot contain more than one dot "%s"', $column));
            }
            return \sprintf('`%s`.`%s`', $parts[0], $parts[1]);
        }
        return \sprintf('`%s`', $column);
    }
}
