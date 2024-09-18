<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Throwable;

/**
 * Class resolver from class-name
 * @psalm-internal
 * @internal
 */
final class ClassResolver
{
    /**
     * @var array<class-string, bool>
     */
    private array $resolving_concretes = [];

    /**
     * Constructor
     * @param ContainerInterface $container
     */
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    /**
     * Tries to resolve a class name to an object. No exception is thrown.
     * @template T of object
     * @param class-string<T> $resolver
     * @return ClassResolverResult<T>
     */
    public function tryResolve(string $resolver): ClassResolverResult
    {
        try {
            if (!\class_exists($resolver)) {
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('class "%s" is not found', $resolver)),
                );
            }
            $ref = new ReflectionClass($resolver);

            if (!$ref->isInstantiable()) {
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('class "%s" is not instantiable', $resolver)),
                );
            }

            $constructor = $ref->getConstructor();
            if (\is_null($constructor) || $constructor->getNumberOfParameters() === 0) {
                // No constructor or no parameters
                /** @var ClassResolverResult<T> $result */
                $result = ClassResolverResult::resolved($ref->newInstance());
                return $result;
            }

            if (\array_key_exists($resolver, $this->resolving_concretes)) {
                // Circular dependency detected
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('circular dependency detected on "%s"', $resolver)),
                );
            }
            $this->resolving_concretes[$resolver] = true;

            $resolved_params = [];
            foreach ($constructor->getParameters() as $param) {
                $p = $this->tryResolveParameter($param);
                if (!$p->isResolved()) {
                    /** @var ClassResolverResult<never> */
                    return $p;
                }
                $resolved_params[] = $p->getInstance();
            }

            $instance = $ref->newInstanceArgs($resolved_params);
            \assert($instance instanceof $resolver);

            /** @var ClassResolverResult<T> $result */
            $result = ClassResolverResult::resolved($instance);

            return $result;
        } catch (Throwable $e) {
            return ClassResolverResult::failed($e);
        } finally {
            unset($this->resolving_concretes[$resolver]);
        }
    }

    /**
     * Resolves a class name to an object.
     *
     * @template T of object
     * @param class-string<T> $resolver
     * @return T
     * @throws ResolveFailedException
     */
    public function resolve(string $resolver): object
    {
        $result = $this->tryResolve($resolver);
        if (!$result->isResolved()) {
            throw new ResolveFailedException($resolver, $result->error);
        }
        return $result->getInstance();
    }

    /**
     * Tries to resolve ReflectionParameter to a value. No exception is thrown.
     * @param ReflectionParameter $param
     * @return ClassResolverResult<mixed>
     */
    public function tryResolveParameter(ReflectionParameter $param): ClassResolverResult
    {
        try {
            if ($param->isVariadic()) {
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('variadic parameter %s is not supported', $param->getName())),
                );
            }
            if (!$param->hasType()) {
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('type of "%s" is not defined. All constructor properties must have type.', $param->getName())),
                );
            }

            $type = $param->getType();
            \assert(!\is_null($type));

            if ($type instanceof ReflectionNamedType && $this->container->has($type->getName())) {
                return ClassResolverResult::resolved($this->container->get($type->getName()));
            } elseif ($type instanceof ReflectionUnionType) {
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('union type is not supported for parameter "%s"', $param->getName())),
                );
            } elseif ($type instanceof ReflectionIntersectionType) {
                return ClassResolverResult::failed(
                    new ReflectionException(\sprintf('intersection type is not supported for parameter "%s"', $param->getName())),
                );
            }

            if ($param->isDefaultValueAvailable()) {
                return ClassResolverResult::resolved($param->getDefaultValue());
            } elseif ($param->allowsNull()) {
                return ClassResolverResult::resolved(null);
            }

            return ClassResolverResult::failed(
                new ReflectionException(\sprintf('unknown type for parameter "%s"', $param->getName())),
            );
        } catch (Throwable $e) {
            return ClassResolverResult::failed(
                new ReflectionException(\sprintf('failed to resolve parameter "%s"', $param->getName()), 0, $e),
            );
        }
    }

    /**
     * Resolves ReflectionParameter to a value.
     *
     * @param ReflectionParameter $param
     * @return mixed
     */
    public function resolveParameter(ReflectionParameter $param): mixed
    {
        $result = $this->tryResolveParameter($param);

        if (!$result->isResolved()) {
            throw new ResolveFailedException($param->getName(), $result->error);
        }
        return $result->getInstance();
    }
}
