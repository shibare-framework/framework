<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license MIT
 */

namespace Shibare\HttpServer\Route;

use Shibare\HttpServer\HttpHandler\HttpHandler;
use Shibare\HttpServer\HttpHandler\MiddlewaresHandler;
use Shibare\Contracts\HttpServer\NotFoundHandlerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shibare\Contracts\HttpServer\RouteResolverInterface;

class RouteHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route_resolver = $this->container->get(RouteResolverInterface::class);
        \assert($route_resolver instanceof RouteResolverInterface);

        $route = $route_resolver->resolve($request->getMethod(), $request->getUri()->getPath());

        if (\is_null($route)) {
            if ($this->container->has(NotFoundHandlerInterface::class)) {
                $not_found_handler = $this->container->get(NotFoundHandlerInterface::class);
                \assert($not_found_handler instanceof NotFoundHandlerInterface);
                return $not_found_handler->handleNotFound($request);
            }
            $response_factory = $this->container->get(ResponseFactoryInterface::class);
            \assert($response_factory instanceof ResponseFactoryInterface);
            return $response_factory->createResponse(404);
        }

        $http_handler = $this->container->get(HttpHandler::class);
        \assert($http_handler instanceof HttpHandler);
        $http_handler->setHandlerName($route->getHandler());

        $middlewares_handler = $this->container->get(MiddlewaresHandler::class);
        \assert($middlewares_handler instanceof MiddlewaresHandler);

        $middlewares_handler->setHandler($http_handler);
        $middlewares_handler->setMiddlewares($route->getMiddlewares());

        return $middlewares_handler->handle($request);
    }
}
