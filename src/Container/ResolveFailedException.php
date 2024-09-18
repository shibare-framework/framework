<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use RuntimeException;
use Throwable;

/**
 * Exception when failed to resolve class
 * @package Shibare\Container
 */
class ResolveFailedException extends RuntimeException
{
    public function __construct(string $class_name, Throwable $previous = null)
    {
        parent::__construct(\sprintf('Failed to resolve class %s', $class_name), 0, $previous);
    }
}
