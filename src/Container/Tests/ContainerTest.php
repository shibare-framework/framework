<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Shibare\Container\ClassResolver;
use Shibare\Container\ClassResolverResult;
use Shibare\Container\Container;
use Shibare\Container\ContainerAwareInterface;
use Shibare\Container\ContainerException;
use Shibare\Container\ResolveFailedException;
use stdClass;

#[CoversClass(Container::class)]
#[UsesClass(ClassResolver::class)]
#[UsesClass(ResolveFailedException::class)]
#[UsesClass(ClassResolverResult::class)]
final class ContainerTest extends TestCase
{
    /** @var resource|null $resource */
    private $resource = null;

    #[After]
    protected function closeResource(): void
    {
        if ($this->resource !== null) {
            \fclose($this->resource);
            $this->resource = null;
        }
    }

    #[Test]
    public function testBind(): void
    {
        $container = new Container();

        $container->bind('a', 'A');
        self::assertSame('A', $container->get('a'));
    }

    #[Test]
    public function testCannotRebind(): void
    {
        $container = new Container();

        $container->bind('a', 'A');

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('"a" is already registered to container');

        $container->bind('a', 'B');
    }

    #[Test]
    public function testCannotBindResource(): void
    {
        $container = new Container();

        $resource = \fopen('php://memory', 'r');
        \assert($resource !== false);
        $this->resource = $resource;
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('resource cannot be registered to container to avoid memory leak');

        $container->bind('a', $this->resource);
    }

    #[Test]
    public function testUnbind(): void
    {
        $container = new Container();

        $container->bind('a', 'A');
        self::assertTrue($container->has('a'));

        $container->unbind('a');
        self::assertFalse($container->has('a'));

        $container->bind('b', 'B');
        self::assertTrue($container->has('b'));

        $container->unbindAll();
        self::assertFalse($container->has('a'));
        self::assertFalse($container->has('b'));
    }

    /**
     * @return iterable<string, array{0: class-string, 1: mixed, 2: mixed}>
     */
    public static function getClasses(): iterable
    {
        $concrete = new stdClass();
        yield 'concrete' => [stdClass::class, $concrete, $concrete];

        $concrete2 = new stdClass();
        $callable = fn (): stdClass => $concrete2;
        yield 'callable' => [stdClass::class, $callable, $concrete2];
    }

    /**
     * @param class-string $class_name
     * @param mixed $resolver
     * @param mixed $expected
     * @return void
     */
    #[Test]
    #[DataProvider('getClasses')]
    public function testGetClass(string $class_name, mixed $resolver, mixed $expected): void
    {
        $container = new Container();

        $container->bind($class_name, $resolver);

        self::assertSame($expected, $container->getClass($class_name));
    }

    #[Test]
    public function testGetClassWithResolve(): void
    {
        $class_resolver = self::createStub(ClassResolver::class);
        $concrete = new stdClass();
        $class_resolver->method('resolve')->willReturn($concrete);

        $container = new Container($class_resolver);

        self::assertSame($concrete, $container->getClass(stdClass::class));
    }

    #[Test]
    public function testGetClassWithResolve2(): void
    {
        include_once __DIR__ . '/ContainerAwareClass.php';

        $concrete = new ContainerAwareClass();
        $class_resolver = self::createStub(ClassResolver::class);
        $class_resolver->method('resolve')->willReturn($concrete);

        $container = new Container($class_resolver);

        $container->bind(ContainerAwareInterface::class, ContainerAwareClass::class);

        self::assertSame($concrete, $container->getClass(ContainerAwareInterface::class));
    }

    #[Test]
    public function testGetClassFailed(): void
    {
        $container = new Container();

        $container->bind(stdClass::class, false);

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('"stdClass" is bound but got "bool"');

        $container->getClass(stdClass::class);
    }

    #[Test]
    public function testGet(): void
    {
        $concrete = new ContainerAwareClass();
        $class_resolver = self::createStub(ClassResolver::class);
        $class_resolver->method('tryResolve')->willReturn(ClassResolverResult::resolved($concrete));
        $class_resolver->method('resolve')->willReturn($concrete);
        $container = new Container($class_resolver);

        self::assertTrue($container->has(ContainerAwareClass::class));
        self::assertSame($concrete, $container->get(ContainerAwareClass::class));
    }

    #[Test]
    public function testGetFailed(): void
    {
        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('"A" is not registered to container');

        $container = new Container();

        self::assertFalse($container->has('A'));
        $container->get('A');
    }

    #[Test]
    public function testGetConcrete(): void
    {
        $container = new Container();

        $concrete = new stdClass();
        $container->bind('a', $concrete);
        self::assertTrue($container->has('a'));
        self::assertSame($concrete, $container->get('a'));
    }

    #[Test]
    public function testGetCallable(): void
    {
        $container = new Container();

        $concrete = new stdClass();
        $callable = fn (): stdClass => $concrete;
        $container->bind('a', $callable);

        self::assertTrue($container->has('a'));
        $actual = $container->get('a');
        self::assertInstanceOf(stdClass::class, $actual);
        self::assertSame($concrete, $actual);
    }

    #[Test]
    public function testGetExplicitResolve(): void
    {
        $class_resolver = self::createStub(ClassResolver::class);
        $concrete = new stdClass();
        $class_resolver->method('tryResolve')->willReturn(ClassResolverResult::resolved($concrete));
        $class_resolver->method('resolve')->willReturn($concrete);

        $container = new Container($class_resolver);

        $container->bind(stdClass::class, stdClass::class);
        self::assertTrue($container->has(stdClass::class));
        self::assertSame($concrete, $container->get(stdClass::class));
    }

    #[Test]
    public function testCall(): void
    {
        $container = new Container();

        $concrete = new stdClass();
        $container->bind('a', 'A');
        $container->bind(stdClass::class, $concrete);

        $result = $container->call(
            fn (string $a, stdClass $concrete): string => $a,
            ['a' => 'a'],
        );

        self::assertSame('a', $result);
    }

    #[Test]
    public function testIsCallable(): void
    {
        $class_resolver = self::createStub(ClassResolver::class);
        $class_resolver->method('tryResolveParameter')->willReturn(ClassResolverResult::failed(new ResolveFailedException('')));
        $container = new Container($class_resolver);

        $container->bind('a', fn (ContainerAwareInterface $i): null => null);

        self::assertFalse($container->has('a'));
    }
}
