<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\HttpHandler;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shibare\HttpServer\ServerRequestRunner;

class MiddlewaresHandler implements RequestHandlerInterface
{
    /** @var list<class-string<MiddlewareInterface>> $middlewares */
    private array $middlewares = [];

    /** @var ?RequestHandlerInterface $handler */
    private ?RequestHandlerInterface $handler = null;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    /**
     * Set middleware names
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @return void
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Set handler
     * @param RequestHandlerInterface $handler
     * @return void
     */
    public function setHandler(RequestHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware_instances = [];
        foreach ($this->middlewares as $middleware) {
            $instance = $this->container->get($middleware);
            if ($instance instanceof MiddlewareInterface === false) {
                throw new InvalidHandlerDefinitionException('Middleware must implement MiddlewareInterface: ' . $middleware);
            }
            $middleware_instances[] = $instance;
        }

        if (\is_null($this->handler)) {
            throw new InvalidHandlerDefinitionException('Handler not set'); // @codeCoverageIgnore
        }

        return (new ServerRequestRunner($middleware_instances, $this->handler))->handle($request);
    }
}
