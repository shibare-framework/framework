<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Contracts;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as BaseContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Container interface
 * @package Shibare\Contracts
 */
interface ContainerInterface extends BaseContainerInterface, BindableInterface
{
    /**
     * Get an class instance from the container
     * @template T of object
     * @param class-string<T> $class_name
     * @return T
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getClass(string $class_name): object;

    /**
     * Check if the container has a resolver
     * @param callable $func
     * @param array<array-key, mixed> $args
     * @return mixed
     * @throws ContainerExceptionInterface
     */
    public function call(callable $func, array $args = []): mixed;
}
