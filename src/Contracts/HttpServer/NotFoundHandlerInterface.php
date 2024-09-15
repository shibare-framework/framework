<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license MIT
 */

namespace Shibare\Contracts\HttpServer;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface NotFoundHandlerInterface
{
    public function handleNotFound(ServerRequestInterface $request): ResponseInterface;
}
