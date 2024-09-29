<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Console\Input;

use Shibare\Console\InputInterface;

/**
 * Console ArgvInput
 * @package Shibare\Console
 * example:
 * ```php
 * $input = new ArgvInput();
 * ```
 */
class ArgvInput implements InputInterface
{
    /** @var array<array-key, mixed> $input */
    public readonly array $input;

    /**
     * ArrayInput constructor
     * @param null|array<array-key, mixed> $input
     */
    public function __construct(
        ?array $input = null,
    ) {
        if (\is_null($input)) {
            $argv = $_SERVER['argv'];
            \assert(\is_array($argv));
            $this->input = $argv;
        } else {
            $this->input = $input;
        }
    }

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
