<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use Psr\Http\Message\MessageInterface;

/**
 * PSR-7 Message about HTTP Headers
 */
trait HasHeaders
{
    /** @var HeaderBag $header_bag */
    protected HeaderBag $header_bag;

    public function getHeaders(): array
    {
        return $this->header_bag->all();
    }

    public function hasHeader(string $name): bool
    {
        return $this->header_bag->has($name);
    }

    public function getHeader(string $name): array
    {
        return $this->header_bag->get($name);
    }

    public function getHeaderLine(string $name): string
    {
        return \implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $new_instance = clone $this;
        $new_instance->header_bag->replace($name, $value);

        return $new_instance;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new_instance = clone $this;
        $new_instance->header_bag->add($name, $value);

        return $new_instance;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new_instance = clone $this;
        $new_instance->header_bag->remove($name);

        return $new_instance;
    }
}
