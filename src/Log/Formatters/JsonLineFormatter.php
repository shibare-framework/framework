<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Log\Formatters;

use Shibare\Log\FormatterInterface;
use Shibare\Log\Rfc5424LogLevel;
use Throwable;

/**
 * JSON-Line formatter
 * @package Shibare\Log\Formatters
 */
class JsonLineFormatter implements FormatterInterface
{
    /**
     * Constructor
     * @param int $json_flags
     * @param string $line_endings
     * @return void
     */
    public function __construct(
        private readonly int $json_flags = \JSON_UNESCAPED_UNICODE,
        private readonly string $line_endings = \PHP_EOL,
    ) {}

    public function format(Rfc5424LogLevel $log_level, string $message, array $context): string
    {
        $ctx = [];

        $ctx['level'] = $log_level->toPsrLogLevel();
        $ctx['message'] = $message;
        if (\array_key_exists('exception', $context) && $context['exception'] instanceof Throwable) {
            $context['exception'] = $this->formatException($context['exception']);
        }
        $ctx['context'] = $context;

        $line = \json_encode($ctx, $this->json_flags) . $this->line_endings;

        $errmsg = \json_last_error_msg();
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            @\trigger_error(\sprintf("Failed to encode JSON: %s", $errmsg), \E_USER_WARNING);
            $line = \strtr($errmsg, "\r\n", "  ") . $this->line_endings;
        }

        return $line;
    }

    /**
     * Format exception object
     * @param Throwable $exception
     * @return array<string, mixed>
     */
    private function formatException(Throwable $exception): array
    {
        $result = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $this->formatTrace($exception->getTrace()),
        ];

        if ($exception->getPrevious()) {
            $result['previous'] = $this->formatException($exception->getPrevious());
        }

        return $result;
    }

    /**
     * Format exception trace
     * @param list<array<string, mixed>> $trace
     * @return string[]
     */
    private function formatTrace(array $trace): array
    {
        $result = [];

        foreach ($trace as $data) {
            if (\array_key_exists('file', $data) && \is_string($data['file']) && \str_contains($data['file'], 'vendor')) {
                // skip vendor files
                continue;
            }

            $result[] = \sprintf('%s%s%s',
                \array_key_exists('class', $data) && \is_string($data['class']) ? $data['class'] : '',
                \array_key_exists('type', $data) && \is_string($data['type']) ? $data['type'] : '',
                \array_key_exists('function', $data) && \is_string($data['function']) ? $data['function'] : '',
            );
        }

        return $result;
    }
}
