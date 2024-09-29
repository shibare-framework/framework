<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Log\Writers;

use Shibare\Log\FormatterInterface;
use Shibare\Log\Rfc5424LogLevel;
use Shibare\Log\WriterInterface;

/**
 * resource writer
 * @package Shibare\Log\Writers
 */
class StreamWriter implements WriterInterface
{
    /**
     * Constructor
     * @param resource $resource
     */
    public function __construct(
        protected mixed $resource,
        protected readonly FormatterInterface $formatter,
    ) {
        if (!\is_resource($this->resource)) {
            throw new \InvalidArgumentException("Argument is not a resource");
        }
    }

    public function __destruct()
    {
        if (\is_resource($this->resource)) {
            @\fclose($this->resource);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write(Rfc5424LogLevel $log_level, string $message, array $context): void
    {
        if (!\is_resource($this->resource)) {
            throw new \RuntimeException("Resource is closed");
        }

        $line = $this->formatter->format($log_level, $message, $context);

        @\fwrite($this->resource, $line);
    }
}
