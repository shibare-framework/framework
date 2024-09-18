<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use Closure;
use Shibare\Contracts\Container as ContainerInterface;
use ReflectionFunction;
use RuntimeException;

/**
 * Represents Dependency Injection Container
 * @package Shibare\Container
 */
final class Container implements ContainerInterface
{
    /** @var array<string, mixed> $resolvers */
    private array $resolvers = [];
    private ClassResolver $class_resolver;

    public function __construct()
    {
        $this->class_resolver = new ClassResolver($this);
    }

    public function bind(string $id, mixed $resolver): void
    {
        if (\array_key_exists($id, $this->resolvers)) {
            throw new RuntimeException(\sprintf('%s is already registered to container', $id));
        }

        $this->forceBind($id, $resolver);
    }

    public function forceBind(string $id, mixed $resolver): void
    {
        if (\is_resource($resolver)) {
            throw new RuntimeException('resource cannot be registered to container to avoid memory leak');
        }

        $this->resolvers[$id] = $resolver;
    }

    public function unbind(string $id): void
    {
        unset($this->resolvers[$id]);
    }

    public function get(string $id): mixed
    {
        $resolver = $id;
        if (\array_key_exists($id, $this->resolvers)) {
            $resolver = $this->resolvers[$id];
        }

        // the order of resolving is important!

        // 1. Concrete object
        if (\is_object($resolver)) {
            // returns the instanced object
            return $resolver;
        }

        // 2. Closure
        if (\is_callable($resolver)) {
            // calls the closure and returns the result
            return $this->call($resolver);
        }

        // 3. Existing class
        if (\is_string($resolver) && \class_exists($resolver)) {
            // instances the class and returns it
            return $this->class_resolver->resolve($resolver);
        }

        throw new RuntimeException(\sprintf('%s is not registered to container', $id));
    }

    public function has(string $id): bool
    {
        $resolver = $id;
        if (\array_key_exists($id, $this->resolvers)) {
            $resolver = $this->resolvers[$id];
        }

        // the order of resolving is important!

        // 1. Concrete object
        if (\is_object($resolver)) {
            return true;
        }

        // 2. Closure
        if (\is_callable($resolver)) {
            // calls the closure and returns the result
            return true;
        }

        // 3. Existing class
        if (\is_string($resolver) && \class_exists($resolver)) {
            // instances the class and returns it
            $result = $this->class_resolver->tryResolve($resolver);
            return $result->isResolved();
        }

        return false;
    }

    public function call(callable $func, array $args = []): mixed
    {
        $ref = new ReflectionFunction(Closure::fromCallable($func));

        if ($ref->getNumberOfParameters() === 0) {
            // no parameters
            return $ref->invoke();
        }

        $resolvedArgs = [];
        foreach ($ref->getParameters() as $param) {
            if (\array_key_exists($param->getName(), $args)) {
                // the argument is explicitly passed
                $resolvedArgs[] = $args[$param->getName()];
                continue;
            }
            $resolvedArgs[$param->getName()] = $this->class_resolver->resolveParameter($param);
        }

        return $ref->invokeArgs($resolvedArgs);
    }
}
