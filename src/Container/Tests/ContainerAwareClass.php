<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use Shibare\Container\ContainerAwareInterface;
use Shibare\Container\ContainerAwareTrait;

class ContainerAwareClass implements ContainerAwareInterface
{
    use ContainerAwareTrait;
}
