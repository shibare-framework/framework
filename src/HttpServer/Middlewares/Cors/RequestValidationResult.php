<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\Middlewares\Cors;

/**
 * @package Shibare\HttpServer\Cors
 */
enum RequestValidationResult
{
    /**
     * Origin header not found
     */
    case ORIGIN_NOT_FOUND;

    /**
     * The request is same-origin
     */
    case SAME_ORIGIN;

    /**
     * The request origin is not allowed in the list
     */
    case ORIGIN_NOT_ALLOWED;

    /**
     * The request method is not allowed
     */
    case METHOD_NOT_ALLOWED;

    /**
     * The request headers are not allowed
     */
    case HEADERS_NOT_ALLOWED;

    /**
     * The request origin is valid and cross origin
     */
    case VALID_CROSS_ORIGIN;
}
