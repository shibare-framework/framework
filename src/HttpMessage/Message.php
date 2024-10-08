<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use Psr\Http\Message\MessageInterface;

/**
 * PSR-7 Message implementation abstraction
 * @package Shibare\HttpMessage
 */
abstract class Message implements MessageInterface
{
    use HasBody;
    use HasHeaders;
    use HasProtocolVersion;

    /**
     * @param array<string, mixed> $headers
     * @return void
     */
    public function __construct(array $headers = [])
    {
        $this->header_bag = new HeaderBag($headers);
    }

    public function __clone()
    {
        // Clone instance properties
        $this->header_bag = clone $this->header_bag;
    }
}
