<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\Middlewares\Auth;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shibare\Contracts\HttpServer\Auth\AuthenticatableInterface;
use Shibare\Contracts\HttpServer\Auth\AuthenticateByTokenInterface;
use Shibare\HttpServer\Middlewares\Auth\BearerAuthenticateMiddleware;

#[CoversClass(BearerAuthenticateMiddleware::class)]
final class BearerAuthenticateMiddlewareTest extends TestCase
{
    #[Test]
    public function testNoAuthorization(): void
    {
        $middleware = new BearerAuthenticateMiddleware(
            $response_factory = $this->createMock(ResponseFactoryInterface::class),
            $stream_factory = $this->createMock(StreamFactoryInterface::class),
            $this->createStub(AuthenticateByTokenInterface::class),
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn([]);

        $expected = $this->createUnauthorized(
            $response_factory,
            $stream_factory,
        );

        $actual = $middleware->process(
            $request,
            $this->createStub(RequestHandlerInterface::class),
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testNoBearerToken(): void
    {
        $middleware = new BearerAuthenticateMiddleware(
            $response_factory = $this->createMock(ResponseFactoryInterface::class),
            $stream_factory = $this->createMock(StreamFactoryInterface::class),
            $this->createStub(AuthenticateByTokenInterface::class),
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn(['Basic auth']);

        $expected = $this->createUnauthorized(
            $response_factory,
            $stream_factory,
        );

        $actual = $middleware->process(
            $request,
            $this->createStub(RequestHandlerInterface::class),
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testAccountNotFound(): void
    {
        $middleware = new BearerAuthenticateMiddleware(
            $response_factory = $this->createMock(ResponseFactoryInterface::class),
            $stream_factory = $this->createMock(StreamFactoryInterface::class),
            $mock = $this->createMock(AuthenticateByTokenInterface::class),
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn(['Bearer token']);

        $mock->expects(self::once())
            ->method('authenticateByToken')
            ->with('token')
            ->willReturn(null);

        $expected = $this->createUnauthorized(
            $response_factory,
            $stream_factory,
        );

        $actual = $middleware->process(
            $request,
            $this->createStub(RequestHandlerInterface::class),
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testValidAccount(): void
    {
        $middleware = new BearerAuthenticateMiddleware(
            $this->createMock(ResponseFactoryInterface::class),
            $this->createMock(StreamFactoryInterface::class),
            $mock = $this->createMock(AuthenticateByTokenInterface::class),
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn(['Bearer token']);

        $mock->expects(self::once())
            ->method('authenticateByToken')
            ->with('token')
            ->willReturn($stub = $this->createStub(AuthenticatableInterface::class));

        $request->expects(self::once())
            ->method('withAttribute')
            ->with(AuthenticatableInterface::class, $stub)
            ->willReturn($request2 = $this->createStub(ServerRequestInterface::class));

        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects(self::once())
            ->method('handle')
            ->with($request2)
            ->willReturn($response = $this->createStub(ResponseInterface::class));

        $actual = $middleware->process(
            $request,
            $handler,
        );

        self::assertSame($response, $actual);
    }

    private function createUnauthorized(
        ResponseFactoryInterface&MockObject $response_factory,
        StreamFactoryInterface&MockObject $stream_factory,
    ): ResponseInterface {
        $response_factory->expects(self::once())
            ->method('createResponse')
            ->with(401)
            ->willReturn($response1 = $this->createMock(ResponseInterface::class));

        $response1->expects(self::once())
            ->method('withHeader')
            ->with('Content-Length', '26')
            ->willReturn($response2 = $this->createMock(ResponseInterface::class));

        $stream_factory->expects(self::once())
            ->method('createStream')
            ->with('{"message":"Unauthorized"}')
            ->willReturn($stream = $this->createStub(StreamInterface::class));
        $response2->expects(self::once())
            ->method('withBody')
            ->with($stream)
            ->willReturn($response3 = $this->createMock(ResponseInterface::class));

        return $response3;
    }
}
