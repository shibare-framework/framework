<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use ReflectionClass;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Izayoi\Attributes\PrimaryKey;

/**
 * @template TEntity of object
 */
class ReflectionEntity
{
    private readonly string $table_name;
    /** @var string[] $primary_keys */
    private readonly array $primary_keys;

    /**
     * @param class-string<TEntity> $name
     */
    public function __construct(
        string $name,
    ) {
        if (!\class_exists($name)) {
            throw new InvalidEntityDefinitionException($name . ' is not a class');
        }
        $ref = new ReflectionClass($name);
        if (!$ref->isInstantiable()) {
            throw new InvalidEntityDefinitionException($name . ' cannot instantiate');
        }
        $entityAttrs = $ref->getAttributes(Entity::class);
        if (\count($entityAttrs) === 0) {
            throw new InvalidEntityDefinitionException($name . ' does not have Entity Attribute');
        }
        $this->table_name = $entityAttrs[0]->newInstance()->table;

        /** @var string[] */
        $pks = [];
        foreach ($ref->getProperties() as $prop) {
            $pk = $prop->getAttributes(PrimaryKey::class);
            if (\count($pk) === 0) {
                continue;
            }
            $pks[] = $pk[0]->getName();
        }
        $this->primary_keys = $pks;
    }

    /**
     * Retrieves raw table name
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * Retrieves primary key column names
     * @return array
     * @phpstan-return string[]
     */
    public function getPrimaryKeys(): array
    {
        return $this->primary_keys;
    }
}
