<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Log\Writers;

use Shibare\Log\FormatterInterface;

/**
 * php://stderr writer
 * @package Shibare\Log\Writers
 */
/* readonly */ class StderrWriter extends StreamWriter
{
    /**
     * Constructor
     * @param FormatterInterface $formatter
     */
    public function __construct(
        FormatterInterface $formatter,
    ) {
        $resource = \fopen('php://stderr', 'w');
        \assert(\is_resource($resource));
        parent::__construct($resource, $formatter);
    }
}
