<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Log;

/**
 * Write log message to anywhere
 * @package Shibare\Log
 */
interface WriterInterface
{
    /**
     * Write log message
     * @param Rfc5424LogLevel $log_level log level
     * @param string $message Message line
     * @param array<array-key, mixed> $context Message context
     * @return void
     */
    public function write(Rfc5424LogLevel $log_level, string $message, array $context): void;
}
