<?php

declare(strict_types=1);

/**
 * Class Uri
 * @package Shibare\HttpMessage
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use InvalidArgumentException;

/**
 * Seriously malformed URI has provided
 * @package Shibare\HttpMessage
 */
class MalformedUriException extends InvalidArgumentException {}
