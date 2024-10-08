<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpFactory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shibare\HttpMessage\ServerRequest;

/**
 * PSR-17 ServerRequest factory implementation
 * @package Shibare\HttpFactory
 */
final /* readonly */ class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * {@inheritDoc}
     * @param string $method
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param array<array-key, mixed> $serverParams
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }
}
