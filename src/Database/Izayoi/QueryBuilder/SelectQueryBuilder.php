<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

use Shibare\Database\Izayoi\SelectQueryBuilderInterface;

class SelectQueryBuilder implements SelectQueryBuilderInterface
{
    use FromQueryBuilderTrait;
    use GroupByQueryBuilderTrait;
    use JoinQueryBuilderTrait;
    use OrderByQueryBuilderTrait;
    use SelectQueryBuilderTrait;
    use WhereQueryBuilderTrait;
    use QuotableTrait;

    protected function getBaseTableName(): string
    {
        if (\is_null($this->table_name)) {
            throw new \RuntimeException('Table name is not set'); // @codeCoverageIgnore
        }
        return $this->table_name;
    }

    public function union(SelectQueryBuilderInterface|array $builder): array
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
}
