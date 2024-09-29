<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Tests\Route;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Shibare\HttpServer\Route\RouteResolver;
use Shibare\HttpServer\Tests\TestCase;

#[CoversClass(RouteResolver::class)]
final class RouteHandlerTest extends TestCase
{
    #[Test]
    public function testHandle(): void
    {
        $this->markTestIncomplete();
    }
}
