<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console\Output;

use Shibare\Console\OutputInterface;

/**
 * Console ArrayOutput
 * @package Shibare\Console
 */
final class ArrayOutput implements OutputInterface
{
    /** @var string[] $lines */
    private array $lines = [];

    public function writeLine(string $line): void
    {
        $this->lines[] = $line;
    }

    /**
     * Get outputted lines
     * @return string[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }
}
