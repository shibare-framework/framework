<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Shibare\Console\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Console\Commands\ListCommand;
use Shibare\Console\Input\ArrayInput;
use Shibare\Console\Kernel;
use Shibare\Console\Output\ArrayOutput;

/**
 * Class KernelTest
 * @package Shibare\Console\Tests
 */
#[CoversClass(Kernel::class)]
#[CoversClass(ArrayInput::class)]
#[CoversClass(ArrayOutput::class)]
#[CoversClass(ListCommand::class)]
final class KernelTest extends TestCase
{
    #[Test]
    public function testRun(): void
    {
        $kernel = new Kernel();
        $input = new ArrayInput([]);
        $output = new ArrayOutput();

        self::assertSame(0, $kernel->run($input, $output));

        self::assertSame([
            'list - List all commands',
        ], $output->getLines());
    }
}
