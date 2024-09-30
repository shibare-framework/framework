<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi\QueryBuilder;

/**
 * Implements of QueryFromInterface
 * @phpstan-require-implements QueryFromInterface
 */
trait FromQueryBuilderTrait
{
    /** @var ?string $table_name */
    protected ?string $table_name = null;

    public function from(string $table_name): static
    {
        $this->table_name = $table_name;

        return $this;
    }

    protected function buildFrom(): string
    {
        if (\is_null($this->table_name)) {
            throw new \InvalidArgumentException('Table name is required');
        }
        return \sprintf('FROM %s', $this->quoteTableName($this->table_name));
    }

    public abstract function quoteTableName(string $table_name): string;
}
