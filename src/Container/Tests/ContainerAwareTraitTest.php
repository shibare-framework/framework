<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\Container\Tests;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Shibare\Container\ContainerAwareInterface;
use Shibare\Container\ContainerAwareTrait;

#[CoversTrait(ContainerAwareTrait::class)]
final class ContainerAwareTraitTest extends TestCase
{
    public function testSetContainer(): void
    {
        $container = self::createStub(ContainerInterface::class);
        $trait = new class implements ContainerAwareInterface {
            use ContainerAwareTrait;

            public function getContainer(): ?ContainerInterface
            {
                return $this->container;
            }
        };
        $trait->setContainer($container);
        self::assertSame($container, $trait->getContainer());
    }
}
