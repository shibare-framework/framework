<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
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
