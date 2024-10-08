<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console\Input;

use Shibare\Console\InputInterface;

/**
 * Console ArrayInput
 * @package Shibare\Console
 * example:
 * ```php
 * $input = new ArrayInput([
 *     'help',
 *     '--verbose' => 'true',
 * ]);
 * ```
 */
final readonly class ArrayInput implements InputInterface
{
    /**
     * ArrayInput constructor
     * @param array<array-key, scalar> $input
     */
    public function __construct(
        public array $input,
    ) {}

    public function getCommandName(): ?string
    {
        foreach ($this->input as $key => $value) {
            if (\is_int($key) && \is_string($value) && !\str_starts_with($value, '-')) {
                return $value;
            }
        }
        return null;
    }
}
