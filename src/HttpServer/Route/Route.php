<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license MIT
 */

namespace Shibare\HttpServer\Route;

use JsonSerializable;
use Psr\Http\Server\MiddlewareInterface;
use Shibare\Contracts\HttpServer\RouteInterface;

/**
 * Route information
 */
final class Route implements RouteInterface, JsonSerializable
{
    /** @var array<string, string> $path_attributes */
    private array $path_attributes = [];

    /**
     * Constructor
     * @param string $method
     * @param string $path
     * @param class-string $handler
     * @param list<class-string<MiddlewareInterface>> $middlewares
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly string $handler,
        public readonly array $middlewares = [],
    ) {}

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function setPathAttribute(string $key, string $value): void
    {
        $this->path_attributes[$key] = $value;
    }

    public function getPathAttribute(string $key): ?string
    {
        if (\array_key_exists($key, $this->path_attributes)) {
            return $this->path_attributes[$key];
        }
        return null;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'method' => $this->method,
            'path' => $this->path,
            'handler' => $this->handler,
            'middlewares' => $this->middlewares,
            'path_attributes' => $this->path_attributes,
        ];
    }
}
