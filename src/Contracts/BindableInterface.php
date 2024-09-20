<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Contracts;

use Psr\Container\ContainerExceptionInterface;

/**
 * Bindable interface
 * @package Shibare\Contracts
 */
interface BindableInterface
{
    /**
     * Bind a resolver to the container
     * @param string $id
     * @param mixed $resolver
     * @return void
     * @throws ContainerExceptionInterface when resource will be registered or already registered id
     */
    public function bind(string $id, mixed $resolver): void;

    /**
     * Force bind a resolver to the container
     * @param string $id
     * @param mixed $resolver
     * @return void
     * @throws ContainerExceptionInterface when resource will be registered
     */
    public function forceBind(string $id, mixed $resolver): void;

    /**
     * Unbind a resolver from the container
     * @param string $id
     * @return void
     */
    public function unbind(string $id): void;

    /**
     * Unbind all resolvers from the container
     * @return void
     */
    public function unbindAll(): void;
}
