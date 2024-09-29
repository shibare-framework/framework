<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

/**
 * Converts iterable items to Collection instance
 * @template T of object
 */
interface CollectionFactoryInterface
{
    /**
     * Creates collection
     * @param iterable<array-key, mixed> $items
     * @return T
     */
    public function factory(iterable $items): object;
}
