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
use Shibare\Log\Writers\StdoutWriter;

/**
 * Class StdoutWriterTest
 * @package Shibare\Log\Tests\Writers
 */
#[CoversClass(StdoutWriter::class)]
final class StdoutWriterTest extends TestCase
{
    #[Test]
    public function testConstructor(): void
    {
        $formatter = self::createStub(FormatterInterface::class);
        $writer = new StdoutWriter($formatter);
        self::assertInstanceOf(StdoutWriter::class, $writer);
    }
}
