<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use Psr\Container\ContainerInterface;

/**
 * Container Aware Trait
 * @package Shibare\Container
 * @phpstan-require-implements ContainerAwareInterface
 */
trait ContainerAwareTrait
{
    /**
     * Container instance
     * @var ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * Set container instance
     * @param ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
