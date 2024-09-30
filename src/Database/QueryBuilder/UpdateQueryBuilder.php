<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\QueryBuilder;

use Shibare\Database\UpdateQueryBuilderInterface;

class UpdateQueryBuilder implements UpdateQueryBuilderInterface
{
    use FromQueryBuilderTrait;
    use WhereQueryBuilderTrait;
    use QuotableTrait;

    /** @var array<string, scalar> $values */
    protected ?array $values = null;

    public function set(array $values): static
    {
        $this->values = $values;

        return $this;
    }

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
        if (\is_null($this->values)) {
            throw new \InvalidArgumentException('Values is required');
        }

        $sql = \sprintf(
            'UPDATE %s SET %s %s',
            $this->quoteTableName($this->getBaseTableName()),
            \implode(', ', \array_map(fn(string $k): string => $this->quoteColumnName($k) . ' = ?', \array_keys($this->values))),
            $where['sql'],
        );

        $bindings = \array_merge(\array_values($this->values), $where['bindings']);

        return compact('sql', 'bindings');
    }
}
