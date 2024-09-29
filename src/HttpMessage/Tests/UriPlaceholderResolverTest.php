<?php

declare(strict_types=1);

/**
 * Class UriTest
 * @package Shibare\HttpMessage
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Shibare\HttpMessage\HeaderBag;
use Shibare\HttpMessage\Internal\UriPartsParser;
use Shibare\HttpMessage\ServerRequest;
use Shibare\HttpMessage\Uri;
use Shibare\HttpMessage\UriPlaceholderResolver;

/**
 * Class UriTest
 * @package Shibare\HttpMessage\Tests
 */
#[CoversClass(UriPlaceholderResolver::class)]
#[UsesClass(ServerRequest::class)]
#[UsesClass(HeaderBag::class)]
#[UsesClass(UriPartsParser::class)]
#[UsesClass(Uri::class)]
final class UriPlaceholderResolverTest extends TestCase
{
    #[Test]
    public function testNoPlaceholder(): void
    {
        $resolver = new UriPlaceholderResolver();
        $request = new ServerRequest('GET', '/');

        $actual = $resolver->resolve($request, '/');

        self::assertFalse($actual);
    }

    /**
     * Get resolve data
     * @return iterable<string, array{0: ServerRequest, 1: array<string, string|int>, 2: string}>
     */
    public static function getResolveData(): iterable
    {
        return [
            'Single placeholder' => [
                new ServerRequest('GET', '/users/1'),
                ['user_id' => '1'],
                '/users/{user_id}',
            ],
            'Multiple placeholders' => [
                new ServerRequest('GET', '/users/1/posts/slug'),
                ['user_id' => '1', 'posts' => 'slug'],
                '/users/{user_id}/posts/{posts}',
            ],
        ];
    }

    /**
     * Test resolve
     * @param ServerRequest $request
     * @param array<string, string|int> $expected
     * @param string $path
     * @return void
     */
    #[Test]
    #[DataProvider('getResolveData')]
    public function testResolve(ServerRequest $request, array $expected, string $path): void
    {
        $resolver = new UriPlaceholderResolver();
        $request = $resolver->resolve($request, $path);

        self::assertInstanceOf(ServerRequest::class, $request);
        self::assertSame($expected, $request->getAttributes());
    }
}
