<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console;

use RuntimeException;

/**
 * Console CommandCannotBeInstanciatedException
 * @package Shibare\Console
 */
final class CommandCannotBeInstanciatedException extends RuntimeException
{
    public function __construct(string $commandName)
    {
        parent::__construct("Command {$commandName} cannot be instanciated");
    }
}
