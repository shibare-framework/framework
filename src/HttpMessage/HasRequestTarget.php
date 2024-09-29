<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * PSR-7 Request request target trait
 * @package Shibare\HttpMessage
 */
trait HasRequestTarget
{
    /** @var string|null $request_target */
    protected ?string $request_target = null;

    public function getRequestTarget(): string
    {
        if ($this->request_target !== null) {
            return $this->request_target;
        }

        $target = '/';
        $uri = $this->getUri();
        $path = $uri->getPath();
        if ($path !== '') {
            $target = $path;
        }

        $query = $uri->getQuery();
        if ($query !== '') {
            $target .= '?' . $query;
        }

        return $target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if (\preg_match('#\s#', $requestTarget)) {
            throw new \InvalidArgumentException('Invalid request target provided; cannot contain whitespace');
        }

        $new_instance = clone $this;
        $new_instance->request_target = $requestTarget;

        return $new_instance;
    }

    /**
     * Get request URI
     * @return UriInterface
     */
    abstract public function getUri(): UriInterface;
}
