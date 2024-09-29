<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use Psr\Http\Message\RequestInterface;

/**
 * PSR-7 Method trait
 * @package Shibare\HttpMessage
 */
trait HasMethod
{
    protected string $method = 'GET'; // default

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $new_instance = clone $this;
        // This should not modify the case of the method
        $new_instance->method = $method;

        return $new_instance;
    }
}
