<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console\Commands;

use Shibare\Console\CommandInterface;
use Shibare\Console\InputInterface;
use Shibare\Console\OutputInterface;

/**
 * Console ListCommand
 * @package Shibare\Console\Commands
 */
final class ListCommand implements CommandInterface
{
    /**
     * ListCommand constructor
     * @param CommandInterface[] $commands
     */
    public function __construct(
        private readonly array $commands,
    ) {}

    public function getCommandName(): string
    {
        return 'list';
    }

    public function getCommandDescription(): string
    {
        return 'List all commands';
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->commands as $command) {
            $output->writeLine($command->getCommandName() . ' - ' . $command->getCommandDescription());
        }
        $output->writeLine('list - List all commands');
        return 0;
    }
}
