<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Log\Tests\Formatters;

use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Shibare\Log\Formatters\JsonLineFormatter;
use Shibare\Log\Rfc5424LogLevel;

/**
 * Class JsonLineFormatterTest
 * @package Shibare\Log\Tests\Formatters
 */
#[CoversClass(JsonLineFormatter::class)]
#[CoversClass(Rfc5424LogLevel::class)]
final class JsonLineFormatterTest extends TestCase
{
    #[Test]
    public function testFormat(): void
    {
        $formatter = new JsonLineFormatter();

        $formatted = $formatter->format(Rfc5424LogLevel::ERROR, 'test', ['a' => 'b']);

        self::assertSame('{"level":"error","message":"test","context":{"a":"b"}}' . PHP_EOL, $formatted);
    }

    #[Test]
    public function testFormatError(): void
    {
        $formatter = new JsonLineFormatter();

        $resource = \fopen('php://memory', 'r');
        $formatted = $formatter->format(Rfc5424LogLevel::ERROR, 'test', ['a' => $resource]);

        self::assertSame('Type is not supported' . PHP_EOL, $formatted);
    }

    #[Test]
    public function testFormatException(): void
    {
        $formatter = new JsonLineFormatter();

        $exception = new RuntimeException('This is message', 100, new LogicException('This is inner message', 10));

        $formatted = $formatter->format(Rfc5424LogLevel::ERROR, 'Unhandled exception', compact('exception'));

        self::assertSame('{"level":"error","message":"Unhandled exception","context":{"exception":{"message":"This is message","code":100,"file":"' . \strtr(__FILE__, ['/' => '\\/']) . '","line":54,"trace":[],"previous":{"message":"This is inner message","code":10,"file":"' . \strtr(__FILE__, ['/' => '\\/']) . '","line":54,"trace":[]}}}}' . PHP_EOL, $formatted);
    }
}
