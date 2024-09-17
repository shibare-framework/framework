<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Contracts\HttpServer;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestAwareInterface
{
    /**
     * Set server request
     * @param ServerRequestInterface $request
     * @return void
     */
    public function setServerRequest(ServerRequestInterface $request): void;

    /**
     * Get server request
     * @return null|ServerRequestInterface
     */
    public function getServerRequest(): ?ServerRequestInterface;
}
