<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Contracts\HttpServer\Auth;

interface AuthenticateByTokenInterface
{
    /**
     * Authenticate by token string
     * @param string $token
     * @return null|AuthenticatableInterface return object when authenticated
     */
    public function authenticateByToken(string $token): ?AuthenticatableInterface;
}
