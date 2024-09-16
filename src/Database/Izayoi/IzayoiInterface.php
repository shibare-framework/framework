<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi;

/**
 * @package Shibare\Database\Izayoi
 */
interface IzayoiInterface
{
    /**
     * Creates query builder for specific entity
     * @template TEntity of object
     * @param class-string<TEntity> $entity_name
     * @return QueryBuilderInterface<TEntity>
     */
    public function of(string $entity_name): QueryBuilderInterface;
}
