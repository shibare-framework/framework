<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\HttpHandler;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Shibare\Contracts\HttpServer\ServerRequestAwareInterface;

/**
 * @template T of object
 * @package Shibare\HttpServer
 */
class InputGenerator
{
    /**
     * @param class-string<T> $class_name
     */
    public function __construct(
        private readonly string $class_name,
    ) {
        if (!\class_exists($this->class_name)) {
            throw new InvalidHandlerDefinitionException('Input class not found ' . $this->class_name);
        }
    }

    /**
     * Generate input object
     * @param ServerRequestInterface $request
     * @return T
     */
    public function generateInput(ServerRequestInterface $request): object
    {
        $ref_class = new ReflectionClass($this->class_name);

        $constructor = $ref_class->getConstructor();

        if (\is_null($constructor)) {
            // no constructor definition
            $input = $ref_class->newInstance();

            if ($input instanceof ServerRequestAwareInterface) {
                $input->setServerRequest($request);
            }
            return $input;
        }

        $parameters = $constructor->getParameters();

        if (\count($parameters) === 0) {
            // no parameters
            $input = $ref_class->newInstance();

            if ($input instanceof ServerRequestAwareInterface) {
                $input->setServerRequest($request);
            }
            return $input;
        }

        $args = [];
        $parsed_body = $request->getParsedBody();
        if (\is_object($parsed_body)) {
            $parsed_body = (array) $parsed_body;
        }
        foreach ($parameters as $param) {
            $name = $param->getName();
            $value = $this->gatherParameter($param, $parsed_body);
            $args[$name] = $value;
        }

        $input = $ref_class->newInstanceArgs($args);

        if ($input instanceof ServerRequestAwareInterface) {
            $input->setServerRequest($request);
        }
        return $input;
    }

    /**
     * Gather parameter from parsed body
     * @param ReflectionParameter $parameter
     * @param array<array-key, mixed>|null $parsed_body
     * @return mixed
     * @throws ReflectionException
     * @throws InvalidHandlerDefinitionException
     */
    private function gatherParameter(ReflectionParameter $parameter, array|null $parsed_body): mixed
    {
        $name = $parameter->getName();

        if (\is_null($parsed_body) === false && \array_key_exists($name, $parsed_body)) {
            return $this->parseValue($parameter, $parsed_body[$name]);
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        throw new InvalidHandlerDefinitionException('Parameter not found ' . $name);
    }

    private function parseValue(ReflectionParameter $parameter, mixed $value): mixed
    {
        if ($parameter->getType() === null) {
            return $value;
        }

        $type = $parameter->getType();

        return match (\get_class($type)) {
            ReflectionNamedType::class => $this->parseNamedType($type, $value),
            ReflectionUnionType::class => throw new InvalidHandlerDefinitionException('Union type not supported'),
            ReflectionIntersectionType::class => throw new InvalidHandlerDefinitionException('Intersection type not supported'),
            default => $value,
        };
    }

    private function parseNamedType(ReflectionNamedType $type, mixed $value): mixed
    {
        $type_name = $type->getName();

        if ($type_name === 'int') {
            if (\is_int($value) === false) {
                throw new InputParameterValidationException(\sprintf('Invalid int value: %s', \gettype($value)));
            }
            return (int) $value;
        }

        if ($type_name === 'float') {
            if (\is_float($value) === false) {
                throw new InputParameterValidationException(\sprintf('Invalid float value: %s', \gettype($value)));
            }
            return (float) $value;
        }

        if ($type_name === 'bool') {
            if (\is_bool($value) === false) {
                throw new InputParameterValidationException(\sprintf('Invalid bool value: %s', \gettype($value)));
            }
            return (bool) $value;
        }

        if ($type_name === 'string') {
            if (\is_string($value) === false) {
                throw new InputParameterValidationException(\sprintf('Invalid string value: %s', \gettype($value)));
            }
            return (string) $value;
        }

        if ($type_name === 'array') {
            if (\is_array($value) === false) {
                throw new InputParameterValidationException(\sprintf('Invalid array value: %s', \gettype($value)));
            }
            return (array) $value;
        }

        if ($type_name === 'object') {
            if (\is_object($value) === false) {
                throw new InputParameterValidationException(\sprintf('Invalid object value: %s', \gettype($value)));
            }
            return (object) $value;
        }

        return $value;
    }
}
