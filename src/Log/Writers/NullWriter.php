<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Log\Writers;

use Shibare\Log\Rfc5424LogLevel;
use Shibare\Log\WriterInterface;

/**
 * Discards log
 * @package Shibare\Log\Writer
 */
class NullWriter implements WriterInterface
{
    public function write(Rfc5424LogLevel $log_level, string $message, array $context): void
    {
        // Do nothing
    }
}
