<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Config\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Config\ArrayConfig;
use Shibare\Contracts\Config\ConfigNotFoundException;
use Shibare\Contracts\Config\InvalidConfigException;

#[CoversClass(ArrayConfig::class)]
final class ArrayConfigTest extends TestCase
{
    #[Test]
    public function testGet(): void
    {
        $config = new ArrayConfig([
            'array' => [],
            'string' => 'x',
            'string_array' => [
                'a',
                'b',
            ],
            'non_empty_string' => 'y',
            'non_empty_string_array' => [
                'c',
                'd',
            ],
            'boolean' => true,
            'integer' => 1,
            'float' => 1.5,
        ]);

        self::assertSame([], $config->getArray('array'));
        self::assertSame('x', $config->getString('string'));
        self::assertSame([
            'a',
            'b',
        ], $config->getStringArray('string_array'));
        self::assertSame('y', $config->getNonEmptyString('non_empty_string'));
        self::assertSame([
            'c',
            'd',
        ], $config->getNonEmptyStringArray('non_empty_string_array'));
        self::assertSame(true, $config->getBoolean('boolean'));
        self::assertSame(1, $config->getInteger('integer'));
        self::assertSame(1.5, $config->getFloat('float'));
    }

    #[Test]
    public function testNotFound(): void
    {
        $config = new ArrayConfig([]);

        $this->expectException(ConfigNotFoundException::class);
        $this->expectExceptionMessage('Config "undefined" not found or invalid');

        $config->getArray('undefined');
    }

    #[Test]
    public function testGetInvalidArray(): void
    {
        $config = new ArrayConfig([
            'float' => 1.5,
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"float" is not array, got "double"');

        $config->getArray('float');
    }

    #[Test]
    public function testGetInvalidString(): void
    {
        $config = new ArrayConfig([
            'float' => 1.5,
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"float" is not string, got "double"');

        $config->getString('float');
    }

    #[Test]
    public function testGetInvalidNonEmptyString(): void
    {
        $config = new ArrayConfig([
            'string' => '',
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"string" is empty');

        $config->getNonEmptyString('string');
    }

    #[Test]
    public function testGetInvalidNonEmptyStringArray(): void
    {
        $config = new ArrayConfig([
            'string' => ['a', ''],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"string" has empty value');

        $config->getNonEmptyStringArray('string');
    }

    #[Test]
    public function testGetInvalidBoolean(): void
    {
        $config = new ArrayConfig([
            'array' => [],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"array" is not boolean, got "array"');

        $config->getBoolean('array');
    }

    #[Test]
    public function testGetInvalidInteger(): void
    {
        $config = new ArrayConfig([
            'array' => [],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"array" is not integer, got "array"');

        $config->getInteger('array');
    }

    #[Test]
    public function testGetInvalidFloat(): void
    {
        $config = new ArrayConfig([
            'array' => [],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"array" is not float, got "array"');

        $config->getFloat('array');
    }
}
