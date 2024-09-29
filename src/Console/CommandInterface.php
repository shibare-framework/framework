<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console;

/**
 * Console CommandInterface
 * @package Shibare\Console
 */
interface CommandInterface
{
    /**
     * Get command name
     * @return string e.g. help
     */
    public function getCommandName(): string;

    /**
     * Get command description
     * @return string e.g. This is a help command
     */
    public function getCommandDescription(): string;

    /**
     * Execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int exit code, 0 when success
     */
    public function execute(InputInterface $input, OutputInterface $output): int;
}
