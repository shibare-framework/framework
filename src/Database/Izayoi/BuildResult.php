<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Izayoi;

final class BuildResult
{
    /**
     * @param iterable<array-key, mixed> $results
     */
    public function __construct(
        private readonly iterable $results,
    ) {}

    /**
     * @return iterable<array-key, mixed>
     */
    public function toRawResult(): iterable
    {
        return $this->results;
    }

    /**
     * @template T of object
     * @param CollectionFactoryInterface<T> $factory
     * @return T
     */
    public function toCollection(CollectionFactoryInterface $factory): object
    {
        return $factory->factory($this->results);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return \iterator_to_array($this->results);
    }
}
