<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\PDO;

use PDO;
use Shibare\Database\Izayoi\BuildResult;
use Shibare\Database\Izayoi\Internal\GroupByQueryBuilderTrait;
use Shibare\Database\Izayoi\Internal\JoinQueryBuilderTrait;
use Shibare\Database\Izayoi\Internal\OrderByQueryBuilderTrait;
use Shibare\Database\Izayoi\Internal\SelectQueryBuilderTrait;
use Shibare\Database\Izayoi\Internal\WhereQueryBuilderTrait;
use Shibare\Database\Izayoi\QueryBuilderInterface;

class QueryBuilder implements QueryBuilderInterface
{
    use GroupByQueryBuilderTrait;
    use JoinQueryBuilderTrait;
    use OrderByQueryBuilderTrait;
    use SelectQueryBuilderTrait;
    use WhereQueryBuilderTrait;

    public function __construct(
        private PDO $pdo,
        private string $table_name,
    ) {}

    private function getBaseTableName(): string
    {
        return $this->table_name;
    }

    public function firstOrNull(): ?object
    {
        $sql_bindings = $this->buildRawSqlAndBindings();

        $stmt = $this->pdo->prepare($sql_bindings['sql']);
        $stmt->execute($sql_bindings['bindings']);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (\is_object($result) === false) {
            return null;
        }

        return $result;
    }

    public function get(): BuildResult
    {
        $sql_bindings = $this->buildRawSqlAndBindings();

        $stmt = $this->pdo->prepare($sql_bindings['sql']);
        $stmt->execute($sql_bindings['bindings']);

        return new BuildResult($stmt);
    }

    public function executeRawQuery(string $sql, array $bindings): iterable
    {
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($bindings);
        \assert(!\is_bool($stmt));

        return $stmt;
    }

    public function union(QueryBuilderInterface|array $builder): BuildResult
    {
        $builders = \is_array($builder) ? $builder : [$builder];

        $sql_list = [];
        $bindings_list = [];
        foreach ($builders as $b) {
            $sqlBindings = $b->buildRawSqlAndBindings();
            $sql_list[] = $sqlBindings['sql'];
            $bindings_list = \array_merge($bindings_list, $sqlBindings['bindings']);
        }

        $stmt = $this->pdo->prepare(\implode(' UNION ', $sql_list));
        $stmt->execute($bindings_list);

        return new BuildResult($stmt);
    }

    public function buildRawSqlAndBindings(): array
    {
        $select = $this->buildSelect();
        $from = $this->buildFrom();
        $join = $this->buildJoin();
        $where = $this->buildWhere();
        $groupBy = $this->buildGroupBy();
        $orderBy = $this->buildOrderBy();

        $sql = \implode(' ', [
            $select,
            $from,
            $join,
            $where['sql'],
            $groupBy,
            $orderBy,
        ]);

        $bindings = $where['bindings'];

        return compact('sql', 'bindings');
    }

    private function buildFrom(): string
    {
        return \sprintf('FROM %s', $this->getBaseTableName());
    }
}
