<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Middlewares\Auth;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shibare\Contracts\HttpServer\Auth\AuthenticatableInterface;
use Shibare\Contracts\HttpServer\Auth\AuthenticateByTokenInterface;

class BearerAuthenticateMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected readonly ResponseFactoryInterface $response_factory,
        protected readonly StreamFactoryInterface $stream_factory,
        protected readonly AuthenticateByTokenInterface $auth,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeader('Authorization');
        if (\count($authorization) !== 1 || !\str_starts_with($authorization[0], 'Bearer ')) {
            return $this->handleUnauthorized();
        }
        $token = \substr($authorization[0], \strlen('Bearer '));

        $authenticatable = $this->auth->authenticateByToken($token);

        if (\is_null($authenticatable)) {
            return $this->handleUnauthorized();
        }

        return $handler->handle($request->withAttribute(AuthenticatableInterface::class, $authenticatable));
    }

    protected function handleUnauthorized(): ResponseInterface
    {
        $body = '{"message":"Unauthorized"}';
        return $this->response_factory->createResponse(401)
            ->withHeader('Content-Length', \strval(\strlen($body)))
            ->withBody($this->stream_factory->createStream($body));
    }
}
