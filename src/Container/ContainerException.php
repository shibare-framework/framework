<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Exception when operation failed in container
 * @package Shibare\Container
 */
class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
}
