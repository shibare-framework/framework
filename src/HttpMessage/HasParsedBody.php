<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use Psr\Http\Message\ServerRequestInterface;

/**
 * PSR-7 ParsedBody trait
 * @package Shibare\HttpMessage
 */
trait HasParsedBody
{
    /** @var array<array-key, mixed>|object|null */
    protected array|object|null $parsed_body = null;

    /**
     * Get parsed body
     * @return array<array-key, mixed>|object|null
     */
    public function getParsedBody(): array|object|null
    {
        return $this->parsed_body;
    }

    /**
     * With parsed body
     * @param mixed $data
     * @return ServerRequestInterface
     */
    public function withParsedBody(mixed $data): ServerRequestInterface
    {
        $new_instance = clone $this;

        if (!\is_array($data) && !\is_object($data) && $data !== null) {
            throw new \InvalidArgumentException('Invalid parsed body');
        }
        $new_instance->parsed_body = $data;

        return $new_instance;
    }
}
