<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

/**
 * @template T of object
 */
interface ObjectMapperInterface
{
    /**
     * @param mixed $value
     * @return object
     * @psalm-return T
     */
    public function fromColumnToObject(mixed $value): object;

    /**
     * @param object $obj
     * @psalm-param T $obj
     * @return mixed
     */
    public function fromObjectToColumn(object $obj): mixed;
}
