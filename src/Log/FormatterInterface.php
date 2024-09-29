<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Log;

/**
 * Format log message
 * @package Shibare\Log
 */
interface FormatterInterface
{
    /**
     * Format log message
     * @param Rfc5424LogLevel $log_level log level
     * @param string $message Message line
     * @param array<array-key, mixed> $context Message context
     * @return string formatted message
     */
    public function format(Rfc5424LogLevel $log_level, string $message, array $context): string;
}
