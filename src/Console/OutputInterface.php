<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console;

/**
 * Console OutputInterface
 * @package Shibare\Console
 */
interface OutputInterface
{
    /**
     * Write a line
     * @param string $line
     * @return void
     */
    public function writeLine(string $line): void;
}
