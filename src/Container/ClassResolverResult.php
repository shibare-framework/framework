<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Container;

use LogicException;
use Throwable;

/**
 * Class resolver result
 * @package Shibare\Container
 * @template T
 */
final class ClassResolverResult
{
    /**
     * @param null|T $instance when resolved
     * @param null|Throwable $error when failed to resolve
     */
    private function __construct(
        public readonly mixed $instance,
        public readonly ?Throwable $error,
    ) {}

    /**
     * Get instance
     * @return T
     * @throws LogicException
     */
    public function getInstance(): mixed
    {
        if (\is_null($this->instance)) {
            throw new LogicException('Instance is not resolved'); // @codeCoverageIgnore
        }
        return $this->instance;
    }

    /**
     * resolved or not
     * @return bool
     */
    public function isResolved(): bool
    {
        return $this->error === null;
    }

    /**
     * Resolve finished successfully
     * @param T $instance
     * @return ClassResolverResult<T>
     */
    public static function resolved(mixed $instance): self
    {
        return new self($instance, null);
    }

    /**
     * Resolve failed
     * @param Throwable $error
     * @return ClassResolverResult<never>
     */
    public static function failed(Throwable $error): self
    {
        /** @var ClassResolverResult<never> $result */
        $result = new self(null, $error);

        return $result;
    }
}
