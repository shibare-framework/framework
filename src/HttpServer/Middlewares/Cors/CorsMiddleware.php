<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Middlewares\Cors;

use Shibare\Contracts\HttpServer\RouteResolverInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
 * @package Ifb\Routing
 */
final class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $response_factory,
        private readonly RequestValidator $validator,
        private readonly RouteResolverInterface $route_resolver,
        private readonly CorsConfig $config,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request_method = $this->isPreflightRequest($request) ? $request->getHeaderLine('Access-Control-Request-Method') : $request->getMethod();
        $route = $this->route_resolver->resolve($request_method, $request->getUri()->getPath());
        if (!$route) {
            return $this->response_factory->createResponse(404);
        }

        $result = $this->validator->validate($request);

        return match ($result) {
            RequestValidationResult::ORIGIN_NOT_FOUND => $this->response_factory->createResponse(400),
            RequestValidationResult::SAME_ORIGIN => $handler->handle($request),
            RequestValidationResult::ORIGIN_NOT_ALLOWED => $this->response_factory->createResponse(400),
            RequestValidationResult::METHOD_NOT_ALLOWED => $this->response_factory->createResponse(405),
            RequestValidationResult::HEADERS_NOT_ALLOWED => $this->response_factory->createResponse(400),
            RequestValidationResult::VALID_CROSS_ORIGIN => $this->processCrossOriginRequest($request, $handler),
        };
    }

    private function processCrossOriginRequest(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $is_preflight_request = $this->isPreflightRequest($request);
        $response = $is_preflight_request ? $this->response_factory->createResponse(204) : $handler->handle($request);

        // Always added
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', $this->config->allow_origin)
            ->withHeader('Vary', $this->config->vary);

        if (!$is_preflight_request) {
            return $response;
        }

        // In pre-flight request
        $response = $response
            ->withHeader('Access-Control-Allow-Methods', $this->config->allow_methods)
            ->withHeader('Access-Control-Allow-Headers', $this->config->allow_headers)
            ->withHeader('Access-Control-Max-Age', (string) $this->config->max_age);

        if (\count($this->config->expose_headers) > 0) {
            $response = $response->withHeader('Access-Control-Expose-Headers', $this->config->expose_headers);
        }
        if ($this->config->allow_credentials) {
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }

    private function isPreflightRequest(ServerRequestInterface $request): bool
    {
        return \strtoupper($request->getMethod()) === 'OPTIONS'
            && $request->hasHeader('Access-Control-Request-Method');
    }
}
