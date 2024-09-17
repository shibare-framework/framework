<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license MIT
 */

namespace Shibare\HttpServer\HttpHandler;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Implementation of ServerRequestAwareInterface
 */
trait ServerRequestAwareTrait
{
    protected ?ServerRequestInterface $request = null;

    /**
     * Set server request
     * @param ServerRequestInterface $request
     * @return void
     */
    public function setServerRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Get server request
     * @return null|ServerRequestInterface
     */
    public function getServerRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }
}
