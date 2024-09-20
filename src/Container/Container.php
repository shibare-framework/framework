<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use Closure;
use Shibare\Contracts\ContainerInterface;
use ReflectionFunction;

/**
 * Represents Dependency Injection Container implementation
 * @package Shibare\Container
 */
final class Container implements ContainerInterface
{
    /** @var array<string, mixed> $resolvers */
    private array $resolvers = [];

    private ClassResolver $class_resolver;

    public function __construct(?ClassResolver $class_resolver = null)
    {
        $this->class_resolver = $class_resolver ?? new ClassResolver($this);
    }

    public function bind(string $id, mixed $resolver): void
    {
        if (\array_key_exists($id, $this->resolvers)) {
            throw new ContainerException(\sprintf('"%s" is already registered to container', $id));
        }

        $this->forceBind($id, $resolver);
    }

    public function forceBind(string $id, mixed $resolver): void
    {
        if (\is_resource($resolver)) {
            throw new ContainerException('resource cannot be registered to container to avoid memory leak');
        }

        $this->resolvers[$id] = $resolver;
    }

    public function unbind(string $id): void
    {
        unset($this->resolvers[$id]);
    }

    public function unbindAll(): void
    {
        $this->resolvers = [];
    }

    public function getClass(string $class_name): object
    {
        if (!\array_key_exists($class_name, $this->resolvers)) {
            return $this->class_resolver->resolve($class_name);
        }

        $resolver = $this->resolvers[$class_name];
        if (\is_object($resolver) && $resolver instanceof $class_name) {
            return $resolver;
        }

        if (\is_callable($resolver)) {
            $result = $this->call($resolver);
            if ($result instanceof $class_name) {
                return $result;
            }
        }

        if (\is_string($resolver) && \class_exists($resolver)) {
            $result = $this->class_resolver->resolve($resolver);
            if ($result instanceof $class_name) {
                return $result;
            }
        }

        throw new ContainerException(\sprintf('"%s" is bound but got "%s"', $class_name, \get_debug_type($resolver)));
    }

    public function get(string $id): mixed
    {
        if (!\array_key_exists($id, $this->resolvers)) {
            if (\class_exists($id)) {
                return $this->class_resolver->resolve($id);
            }
            throw new ResolveFailedException(\sprintf('"%s" is not registered to container', $id));
        }

        $resolver = $this->resolvers[$id];

        if (\is_callable($resolver)) {
            // calls the closure and returns the result
            return $this->call($resolver);
        }

        if (\is_object($resolver)) {
            // returns the instanced object
            return $resolver;
        }

        if (\is_string($resolver) && \class_exists($resolver)) {
            return $this->class_resolver->resolve($resolver);
        }

        return $resolver;
    }

    public function has(string $id): bool
    {
        if (!\array_key_exists($id, $this->resolvers)) {
            if (\class_exists($id)) {
                return $this->class_resolver->tryResolve($id)->isResolved();
            }
            return false;
        }

        $resolver = $this->resolvers[$id];

        if (\is_callable($resolver)) {
            return $this->isCallable($resolver);
        }

        if (\is_object($resolver)) {
            return true;
        }

        if (\is_string($resolver) && \class_exists($resolver)) {
            return $this->class_resolver->tryResolve($resolver)->isResolved();
        }

        return true;
    }

    public function call(callable $func, array $args = []): mixed
    {
        $ref = new ReflectionFunction(Closure::fromCallable($func));

        $resolved_args = [];
        foreach ($ref->getParameters() as $param) {
            $name = $param->getName();
            if (\array_key_exists($name, $args)) {
                // the argument is explicitly passed
                $resolved_args[$name] = $args[$name];
                continue;
            }
            $resolved_args[$name] = $this->class_resolver->resolveParameter($param);
        }

        return $ref->invokeArgs($resolved_args);
    }

    /**
     * Check if the container has a callable resolver
     * @param callable $func
     * @param array<array-key, mixed> $args
     * @return bool
     */
    private function isCallable(callable $func, array $args = []): bool
    {
        $ref = new ReflectionFunction(Closure::fromCallable($func));

        foreach ($ref->getParameters() as $param) {
            $name = $param->getName();
            if (\array_key_exists($name, $args)) {
                continue; // @codeCoverageIgnore
            }
            $result = $this->class_resolver->tryResolveParameter($param);
            if (!$result->isResolved()) {
                return false;
            }
        }

        return true;
    }
}
