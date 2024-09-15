<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Contracts\HttpServer;

use Psr\Http\Server\MiddlewareInterface;

interface RouteInterface
{
    /**
     * Get Handler classname
     * @return class-string
     */
    public function getHandler(): string;

    /**
     * Get middlewares
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getMiddlewares(): array;

    /**
     * Get path attribute
     * @param string $key
     * @return ?string
     */
    public function getPathAttribute(string $key): ?string;

    /**
     * Set path attributes
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setPathAttribute(string $key, string $value): void;
}
