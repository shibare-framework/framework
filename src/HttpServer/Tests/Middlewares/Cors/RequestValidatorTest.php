<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\Middlewares\Cors;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shibare\HttpServer\Middlewares\Cors\CorsConfig;
use Shibare\HttpServer\Middlewares\Cors\RequestValidationResult;
use Shibare\HttpServer\Middlewares\Cors\RequestValidator;

#[CoversClass(CorsConfig::class)]
#[CoversClass(RequestValidationResult::class)]
#[CoversClass(RequestValidator::class)]
final class RequestValidatorTest extends TestCase
{
    #[Test]
    public function testOriginNotFound(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin'], [[]]);
        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::ORIGIN_NOT_FOUND, $result);
    }

    #[Test]
    public function testOriginNotFoundMultipleOrigins(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin'], [['http://example.com', 'https://example.com']]);
        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::ORIGIN_NOT_FOUND, $result);
    }

    #[Test]
    public function testInvalidServerOrigin(): void
    {
        $config = new CorsConfig('http://// localhost', ['http://example.com']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin'], [['http://example.com']]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid server origin setting provided: http://// localhost');

        $validator->validate($request);
    }

    #[Test]
    public function testInvalidOrigin(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin'], [['http://// example.com']]);

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::ORIGIN_NOT_ALLOWED, $result);
    }

    #[Test]
    public function testSameOrigin(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin'], [['http://localhost']]);

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::SAME_ORIGIN, $result);
    }

    #[Test]
    public function testOriginNotAllowed(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin'], [['http://example.org']]);

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::ORIGIN_NOT_ALLOWED, $result);
    }

    #[Test]
    public function testMethodNotFound(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com'], ['GET']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin', 'Access-Control-Request-Method'], [['http://example.com'], []]);

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::METHOD_NOT_ALLOWED, $result);
    }

    #[Test]
    public function testMethodNotAllowed(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com'], ['GET']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(['Origin', 'Access-Control-Request-Method'], [['http://example.com'], ['POST']]);

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::METHOD_NOT_ALLOWED, $result);
    }

    #[Test]
    public function testHeadersNotAllowed(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com'], ['GET'], ['X-Custom-Header']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(
            ['Origin', 'Access-Control-Request-Method', 'Access-Control-Request-Headers'],
            [['http://example.com'], ['GET'], ['X-Custom-Header', 'X-Another-Header']],
        );

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::HEADERS_NOT_ALLOWED, $result);
    }

    #[Test]
    public function testValidCrossOrigin(): void
    {
        $config = new CorsConfig('http://localhost', ['http://example.com'], ['GET'], ['X-Custom-Header']);
        $validator = new RequestValidator($config);

        $request = $this->createServerRequestMock(
            ['Origin', 'Access-Control-Request-Method', 'Access-Control-Request-Headers'],
            [['http://example.com'], ['GET'], ['X-Custom-Header']],
        );

        $result = $validator->validate($request);

        self::assertSame(RequestValidationResult::VALID_CROSS_ORIGIN, $result);
    }

    /**
     * @param array<int, string> $mock_headers
     * @param array<int, string[]> $mock_returns
     * @return ServerRequestInterface
     */
    private function createServerRequestMock(array $mock_headers, array $mock_returns): ServerRequestInterface
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($invoked_count = self::exactly(count($mock_headers)))
            ->method('getHeader')
            ->with(self::callback(static function (string $name) use ($invoked_count, $mock_headers): bool {
                return $name === $mock_headers[$invoked_count->numberOfInvocations() - 1];
            }))
            ->willReturnOnConsecutiveCalls(...$mock_returns);

        return $request;
    }
}
