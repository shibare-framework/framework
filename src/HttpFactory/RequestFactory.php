<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpFactory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Shibare\HttpMessage\Request;

/**
 * PSR-17 Request factory implementation
 * @package Shibare\HttpFactory
 */
final /* readonly */ class RequestFactory implements RequestFactoryInterface
{
    /**
     * Create a new request
     * {@inheritDoc}
     * @param string $method
     * @param string|\Psr\Http\Message\UriInterface $uri
     * @return RequestInterface
     */
    public function createRequest(string $method, mixed $uri): RequestInterface
    {
        return new Request($method, $uri);
    }
}
