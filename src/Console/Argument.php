<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console;

use Attribute;

/**
 * Console Argument
 * @package Shibare\Console
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final /* readonly */ class Argument
{
    /**
     * Argument constructor
     * @param string $name argument name
     * @param bool $required default: false
     * @param null|string $default default value, default: null
     * @param string $description text description, default: ''
     */
    public function __construct(
        public readonly string $name,
        public readonly bool $required = false,
        public readonly ?string $default = null,
        public readonly string $description = '',
    ) {}
}
