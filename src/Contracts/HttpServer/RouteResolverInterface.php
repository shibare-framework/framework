<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Contracts\HttpServer;

interface RouteResolverInterface
{
    /**
     * Resolve route
     * @param string $method
     * @param string $path
     * @return null|RouteInterface null if route not found
     */
    public function resolve(string $method, string $path): ?RouteInterface;
}
