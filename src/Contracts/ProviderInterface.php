<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Contracts;

use Shibare\Contracts\Config\ConfigInterface;

interface ProviderInterface
{
    /**
     * Provide concrete instances to Container
     * @param ContainerInterface $container
     * @param ConfigInterface $config
     * @return void
     */
    public function provide(ContainerInterface $container, ConfigInterface $config): void;
}
