<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use Shibare\Container\ContainerAwareInterface;
use Shibare\Container\ContainerAwareTrait;

class ContainerAwareClass implements ContainerAwareInterface
{
    use ContainerAwareTrait;
}
