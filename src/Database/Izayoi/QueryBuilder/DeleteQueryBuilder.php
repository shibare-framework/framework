<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

use Shibare\Database\Izayoi\DeleteQueryBuilderInterface;

class DeleteQueryBuilder implements DeleteQueryBuilderInterface
{
    use FromQueryBuilderTrait;
    use WhereQueryBuilderTrait;
    use QuotableTrait;

    protected function getBaseTableName(): string
    {
        if (\is_null($this->table_name)) {
            throw new \RuntimeException('Table name is not set'); // @codeCoverageIgnore
        }
        return $this->table_name;
    }

    public function buildRawSqlAndBindings(): array
    {
        $from = $this->buildFrom();
        $where = $this->buildWhere();

        $sql = \sprintf(
            'DELETE FROM %s %s',
            $this->quoteTableName($this->getBaseTableName()),
            $where['sql'],
        );

        $bindings = $where['bindings'];

        return compact('sql', 'bindings');
    }
}
