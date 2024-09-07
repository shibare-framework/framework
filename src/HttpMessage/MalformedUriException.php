<?php

declare(strict_types=1);

/**
 * Class Uri
 * @package Shibare\HttpMessage
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\HttpMessage;

use InvalidArgumentException;

/**
 * Seriously malformed URI has provided
 * @package Shibare\HttpMessage
 */
class MalformedUriException extends InvalidArgumentException {}
