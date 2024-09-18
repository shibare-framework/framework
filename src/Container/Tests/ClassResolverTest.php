<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Shibare\Container\ClassResolver;
use Shibare\Container\Container;
use Shibare\Container\ClassResolverResult;
use Shibare\Container\ResolveFailedException;

#[CoversClass(ClassResolver::class)]
#[CoversClass(ClassResolverResult::class)]
#[CoversClass(ResolveFailedException::class)]
#[UsesClass(Container::class)]
final class ClassResolverTest extends TestCase
{
    #[Test]
    public function testResolveNotExist(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $resolver = new ClassResolver($container);

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class Shibare\Tests\Container\ClassResolverTesta');

        // @phpstan-ignore argument.type
        $resolver->resolve('Shibare\Tests\Container\ClassResolverTesta');
    }

    #[Test]
    public function testResolveEmptyConstructor(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $resolver = new ClassResolver($container);

        $stdClass = $resolver->resolve('\stdClass');
        self::assertInstanceOf(\stdClass::class, $stdClass);
    }

    #[Test]
    public function testResolveCircularDependency(): void
    {
        $container = new Container();
        $resolver = new ClassResolver($container);

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class Shibare\Container\Tests\CircularDepsA');

        $resolver->resolve(CircularDepsA::class);
    }

    #[Test]
    public function testResolve(): void
    {
        $container = new Container();
        $container->bind(ContainerInterface::class, $container);
        $resolver = new ClassResolver($container);

        $actual = $resolver->resolve(ClassResolver::class);

        self::assertInstanceOf(ClassResolver::class, $actual);
    }

    #[Test]
    public function testResolveParameter(): void
    {
        $container = new Container();
        $container->bind(ContainerInterface::class, $container);
        $resolver = new ClassResolver($container);

        $actual = $resolver->resolveParameter(new \ReflectionParameter([ClassResolver::class, '__construct'], 'container'));

        self::assertSame($container, $actual);
    }

    #[Test]
    public function testResolveParameterDefaultValue(): void
    {
        $container = new Container();
        $resolver = new ClassResolver($container);

        $actual = $resolver->resolveParameter(new \ReflectionParameter([StubClass::class, '__construct'], 'c'));

        self::assertSame(1, $actual);
    }

    #[Test]
    public function testResolveParameterIsVariadic(): void
    {
        $container = new Container();
        $resolver = new ClassResolver($container);

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class d');

        $resolver->resolveParameter(new \ReflectionParameter([StubClass::class, '__construct'], 'd'));
    }

    #[Test]
    public function testResolveParameterNotHasType(): void
    {
        $container = new Container();
        $resolver = new ClassResolver($container);

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class a');

        $resolver->resolveParameter(new \ReflectionParameter([StubClass::class, '__construct'], 'a'));
    }

    #[Test]
    public function testResolveParameterBuiltIn(): void
    {
        $container = new Container();
        $resolver = new ClassResolver($container);

        $this->expectException(ResolveFailedException::class);
        $this->expectExceptionMessage('Failed to resolve class b');

        $resolver->resolveParameter(new \ReflectionParameter([StubClass::class, '__construct'], 'b'));
    }
}
