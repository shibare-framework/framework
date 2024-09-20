<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionParameter;
use Shibare\Container\ClassResolver;
use Shibare\Container\Container;
use Shibare\Container\ClassResolverResult;
use Shibare\Container\ResolveFailedException;
use stdClass;

#[CoversClass(ClassResolver::class)]
#[CoversClass(ClassResolverResult::class)]
#[CoversClass(ResolveFailedException::class)]
#[UsesClass(Container::class)]
final class ClassResolverTest extends TestCase
{
    /**
     * @return iterable<string, array{string, ContainerInterface, ClassResolverResult<mixed>}>
     */
    public static function getTryResolve(): iterable
    {
        yield 'stdClass' => [stdClass::class, new Container(), ClassResolverResult::resolved(new stdClass())];

        yield 'class not found' => ['NotFound', new Container(), ClassResolverResult::failed(new ReflectionException('class "NotFound" is not found'))];

        yield 'private class' => [PrivateClass::class, new Container(), ClassResolverResult::failed(new ReflectionException('class "Shibare\Container\Tests\PrivateClass" is not instantiable'))];

        yield 'circular deps' => [CircularDepsA::class, new Container(), ClassResolverResult::failed(new ReflectionException('unknown type for parameter "Shibare\Container\Tests\CircularDepsB $b"'))];

        $container = self::createStub(ContainerInterface::class);
        $container->method('has')->willReturn(true);
        $container->method('get')->willReturn(new stdClass());

        yield 'container has' => [StubClass1::class, $container, ClassResolverResult::resolved(new StubClass1(new stdClass()))];
    }

    #[Before]
    public function setUpClasses(): void
    {
        include_once __DIR__ . '/CircularDepsA.php';
        include_once __DIR__ . '/CircularDepsB.php';
        include_once __DIR__ . '/PrivateClass.php';
        include_once __DIR__ . '/StubClass1.php';
    }

    /**
     * @param class-string $target
     * @param ContainerInterface $container
     * @param ClassResolverResult<mixed> $expected
     * @return void
     */
    #[Test]
    #[DataProvider('getTryResolve')]
    public function testTryResolve(string $target, ContainerInterface $container, ClassResolverResult $expected): void
    {
        $resolver = new ClassResolver($container);

        $actual = $resolver->tryResolve($target);

        self::assertEquals($expected->instance, $actual->instance);
        if (!$expected->isResolved()) {
            self::assertSame($expected->error?->getMessage(), $actual->error?->getMessage());
        }
    }

    #[Test]
    public function testResolve(): void
    {
        $resolver = new ClassResolver(new Container());

        $actual = $resolver->resolve(stdClass::class);

        self::assertInstanceOf(stdClass::class, $actual);
    }

    #[Test]
    public function testResolveFailed(): void
    {
        $resolver = new ClassResolver(new Container());

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class "NotFound"');

        // @phpstan-ignore argument.type
        $resolver->resolve('NotFound');
    }

    /**
     * @return iterable<string, array{ReflectionParameter, ClassResolverResult<mixed>}>
     */
    public static function getTryResolveParameter(): iterable
    {
        yield 'variadic' => [new ReflectionParameter(fn (mixed ...$args) => null, 'args'), ClassResolverResult::failed(new ReflectionException('variadic parameter args is not supported'))];

        yield 'no type' => [new ReflectionParameter(fn ($arg) => null, 'arg'), ClassResolverResult::failed(new ReflectionException('type of "arg" is not defined. All constructor properties must have type.'))];

        yield 'not found' => [new ReflectionParameter(fn (ContainerInterface $arg) => null, 'arg'), ClassResolverResult::failed(new ReflectionException('unknown type for parameter "Psr\Container\ContainerInterface $arg"'))];

        yield 'union type' => [new ReflectionParameter(fn (stdClass|ContainerInterface $arg) => null, 'arg'), ClassResolverResult::failed(new ReflectionException('union type is not supported for parameter "arg"'))];

        yield 'intersection type' => [new ReflectionParameter(fn (stdClass&ContainerInterface $arg) => null, 'arg'), ClassResolverResult::failed(new ReflectionException('intersection type is not supported for parameter "arg"'))];

        yield 'default value' => [new ReflectionParameter(fn (mixed $arg = 'a') => null, 'arg'), ClassResolverResult::resolved('a')];

        yield 'allows null' => [new ReflectionParameter(fn (?ContainerInterface $arg) => null, 'arg'), ClassResolverResult::resolved(null)];
    }

    /**
     * @param ReflectionParameter $param
     * @param ClassResolverResult<never> $expected
     * @return void
     */
    #[Test]
    #[DataProvider('getTryResolveParameter')]
    public function testTryResolveParameter(ReflectionParameter $param, ClassResolverResult $expected): void
    {
        $resolver = new ClassResolver(new Container());

        $actual = $resolver->tryResolveParameter($param);

        self::assertEquals($expected->instance, $actual->instance);
        if (!$expected->isResolved()) {
            self::assertSame($expected->error?->getMessage(), $actual->error?->getMessage());
        }
    }

    #[Test]
    public function testResolveParameter(): void
    {
        $resolver = new ClassResolver(new Container());

        $param = new ReflectionParameter(fn (stdClass $arg) => null, 'arg');

        $actual = $resolver->resolveParameter($param);

        self::assertInstanceOf(stdClass::class, $actual);
    }

    #[Test]
    public function testResolveParameterFailed(): void
    {
        $resolver = new ClassResolver(new Container());

        $param = new ReflectionParameter(fn (ContainerInterface $arg) => null, 'arg');

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class "arg"');

        $resolver->resolveParameter($param);
    }
}
