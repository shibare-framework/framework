<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Log\Tests\Writers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Log\FormatterInterface;
use Shibare\Log\Rfc5424LogLevel;
use Shibare\Log\Writers\StreamWriter;

/**
 * Class StreamWriterTest
 * @package Shibare\Log\Tests\Writers
 */
#[CoversClass(StreamWriter::class)]
final class StreamWriterTest extends TestCase
{
    #[Test]
    public function testStreamWriter(): void
    {
        $formatter = self::createMock(FormatterInterface::class);
        $resource = \fopen('php://memory', 'w');
        \assert(\is_resource($resource));
        $writer = new StreamWriter($resource, $formatter);

        $formatter->expects($this->once())
            ->method('format')
            ->with(Rfc5424LogLevel::EMERGENCY, 'emergency', ['context3' => 'context3']);

        $writer->write(Rfc5424LogLevel::EMERGENCY, 'emergency', ['context3' => 'context3']);
    }

    #[Test]
    public function testStreamIsNotResource(): void
    {
        $formatter = self::createMock(FormatterInterface::class);
        $resource = null;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument is not a resource');

        // @phpstan-ignore argument.type
        new StreamWriter($resource, $formatter);
    }

    #[Test]
    public function testStreamResourceClosed(): void
    {
        $formatter = self::createStub(FormatterInterface::class);
        $resource = \fopen('php://memory', 'r');
        \assert(\is_resource($resource));

        $writer = new StreamWriter($resource, $formatter);

        \fclose($resource);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Resource is closed');

        $writer->write(Rfc5424LogLevel::EMERGENCY, 'emergency', ['context3' => 'context3']);
    }
}
