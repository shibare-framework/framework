<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Log\Writers;

use Shibare\Log\FormatterInterface;

/**
 * php://stdout writer
 * @package Shibare\Log\Writers
 */
/* readonly */ class StdoutWriter extends StreamWriter
{
    /**
     * Constructor
     * @param FormatterInterface $formatter
     */
    public function __construct(
        FormatterInterface $formatter,
    ) {
        $resource = \fopen('php://stdout', 'w');
        \assert(\is_resource($resource));
        parent::__construct($resource, $formatter);
    }
}
