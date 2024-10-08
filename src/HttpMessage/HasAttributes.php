<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use Psr\Http\Message\ServerRequestInterface;

/**
 * PSR-7 Attributes trait
 * @package Shibare\HttpMessage
 */
trait HasAttributes
{
    /** @var array<array-key, mixed> $attributes */
    protected array $attributes = [];

    /**
     * Get attributes
     * @return array<array-key, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, mixed $value): ServerRequestInterface
    {
        $new_instance = clone $this;
        $new_instance->attributes[$name] = $value;

        return $new_instance;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $new_instance = clone $this;
        unset($new_instance->attributes[$name]);

        return $new_instance;
    }
}
