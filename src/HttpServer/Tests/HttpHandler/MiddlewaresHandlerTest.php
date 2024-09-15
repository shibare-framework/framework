<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\HttpServer\Tests\HttpHandler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shibare\HttpServer\HttpHandler\InvalidHandlerDefinitionException;
use Shibare\HttpServer\HttpHandler\MiddlewaresHandler;
use Shibare\HttpServer\ServerRequestRunner;
use Shibare\HttpServer\Tests\TestCase;

#[CoversClass(MiddlewaresHandler::class)]
#[UsesClass(ServerRequestRunner::class)]
final class MiddlewaresHandlerTest extends TestCase
{
    #[Test]
    public function testHandle(): void
    {
        $container = $this->createMockeryMock(ContainerInterface::class);
        $middlewares = [
            self::class,
        ];
        $handler = $this->createMockeryMock(RequestHandlerInterface::class);
        $handler->shouldReceive('handle')
            ->once()
            ->andReturn($response = $this->createMockeryMock(ResponseInterface::class));

        $container->shouldReceive('get')
            ->with(self::class)
            ->once()
            ->andReturn(new class implements MiddlewareInterface {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            });

        $middlewares_handler = new MiddlewaresHandler($container);
        /** @phpstan-ignore argument.type */
        $middlewares_handler->setMiddlewares($middlewares);
        $middlewares_handler->setHandler($handler);

        $request = $this->createMockeryMock(ServerRequestInterface::class);

        $actual = $middlewares_handler->handle($request);

        self::assertSame($response, $actual);
    }

    #[Test]
    public function testInvalidMiddleware(): void
    {
        $container = $this->createMockeryMock(ContainerInterface::class);
        $middlewares = ['invalid_middleware'];
        $handler = $this->createMockeryMock(RequestHandlerInterface::class);

        $this->expectException(InvalidHandlerDefinitionException::class);
        $this->expectExceptionMessage('Middleware must implement MiddlewareInterface: invalid_middleware');

        $container->shouldReceive('get')
            ->with('invalid_middleware')
            ->once()
            ->andReturnNull();

        $middlewares_handler = new MiddlewaresHandler($container);
        /** @phpstan-ignore argument.type */
        $middlewares_handler->setMiddlewares($middlewares);
        $middlewares_handler->setHandler($handler);
        $middlewares_handler->handle($this->createMockeryMock(ServerRequestInterface::class));
    }
}
