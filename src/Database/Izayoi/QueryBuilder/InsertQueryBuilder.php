<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

use InvalidArgumentException;
use Shibare\Database\Izayoi\InsertQueryBuilderInterface;

class InsertQueryBuilder implements InsertQueryBuilderInterface
{
    use QuotableTrait;

    protected ?string $table_name = null;

    /** @var list<array<string, scalar>> $values */
    protected ?array $values = null;

    public function into(string $table_name): static
    {
        $this->table_name = $table_name;

        return $this;
    }

    public function value(array $record): static
    {
        if (\array_is_list($record)) {
            throw new InvalidArgumentException('Record must be associative array');
        }

        return $this->valueList([$record]);
    }

    public function valueList(array $records): static
    {
        if (!\array_is_list($records)) {
            throw new InvalidArgumentException('Records must be list of associative array');
        }

        $this->values = $records;

        return $this;
    }

    public function buildRawSqlAndBindings(): array
    {
        if (\is_null($this->table_name)) {
            throw new InvalidArgumentException('Table name is required');
        }
        if (\is_null($this->values)) {
            throw new InvalidArgumentException('Values is required');
        }

        $sql = \sprintf(
            'INSERT INTO %s (%s) VALUES %s',
            $this->quoteTableName($this->table_name),
            $this->buildColumnNames($this->values),
            $this->buildValuePlaceholder($this->values),
        );
        $bindings = $this->buildBindings($this->values);
        return compact('sql', 'bindings');
    }

    /**
     * @param list<array<array-key, scalar>> $values
     * @return string
     */
    protected function buildColumnNames(array $values): string
    {
        $columns = [];
        foreach ($values as $record) {
            $keys = \array_keys($record);
            if ($columns === []) {
                $columns = $keys;
            } elseif ($columns !== $keys) {
                throw new InvalidArgumentException('Column names must be same');
            }
        }

        return \implode(', ', \array_map(fn(string $column): string => $this->quoteColumnName($column), $columns));
    }

    /**
     * @param list<array<array-key, scalar>> $values
     * @return string
     */
    protected function buildValuePlaceholder(array $values): string
    {
        $result = '';

        foreach ($values as $record) {
            if ($result !== '') {
                $result .= ', ';
            }
            $result .= '(' . \implode(', ', \array_fill(0, \count($record), '?')) . ')';
        }

        return $result;
    }

    /**
     * @param list<array<array-key, scalar>> $values
     * @return list<scalar>
     */
    protected function buildBindings(array $values): array
    {
        $bindings = [];
        foreach ($values as $record) {
            foreach ($record as $value) {
                $bindings[] = $value;
            }
        }

        return $bindings;
    }
}
