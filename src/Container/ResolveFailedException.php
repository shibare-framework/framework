<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Throwable;

/**
 * Exception when failed to resolve class
 * @package Shibare\Container
 */
class ResolveFailedException extends RuntimeException implements NotFoundExceptionInterface
{
    public function __construct(string $class_name, ?Throwable $previous = null)
    {
        parent::__construct(\sprintf('Failed to resolve class "%s"', $class_name), 0, $previous);
    }
}
