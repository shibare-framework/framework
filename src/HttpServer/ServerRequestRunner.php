<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpServer;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * PSR-15 ServerRequest runner Last In First Out middlewares
 * @package Shibare\HttpServer
 * @link https://github.com/httpsoft/http-runner/
 * @link https://github.com/relayphp/Relay.Relay/
 * @link https://github.com/slimphp/Slim/blob/4.x/Slim/MiddlewareDispatcher.php
 * @example
 * ```php
 * return static function (ServerRequestInterface $request): ResponseInterface {
 *     $middlewares = ...; // Add MiddlewareInterface list
 *     $kernel = ... // RequestHandlerInterface
 *     return (new ServerRequestRunner($middlewares, $kernel))->handle($request);
 * };
 */
class ServerRequestRunner implements RequestHandlerInterface
{
    protected RequestHandlerInterface $handler;

    /**
     * Constructor
     * @param iterable<mixed> $middlewares
     * @param RequestHandlerInterface $handler
     */
    public function __construct(iterable $middlewares, RequestHandlerInterface $handler)
    {
        // Set main handler
        $this->handler = $handler;

        // Set middleware stack
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof MiddlewareInterface === false) {
                throw new \InvalidArgumentException('Middleware must be an instance of ' . MiddlewareInterface::class);
            }

            $next = $this->handler;
            $this->handler = new class ($middleware, $next) implements RequestHandlerInterface {
                public function __construct(
                    private readonly MiddlewareInterface $middleware,
                    private readonly RequestHandlerInterface $next,
                ) {}

                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return $this->middleware->process($request, $this->next);
                }
            };
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
