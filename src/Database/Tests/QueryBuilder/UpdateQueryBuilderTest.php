<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\QueryBuilder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Database\QueryBuilder\UpdateQueryBuilder;

#[CoversClass(UpdateQueryBuilder::class)]
final class UpdateQueryBuilderTest extends TestCase
{
    #[Test]
    public function testUpdate(): void
    {
        $builder = new UpdateQueryBuilder();
        $expected = $builder->from('a')->set(['b' => 1])->buildRawSqlAndBindings();

        self::assertSame('UPDATE `a` SET `b` = ? ', $expected['sql']);
        self::assertSame([1], $expected['bindings']);
    }

    #[Test]
    public function testUpdateNotSet(): void
    {
        $builder = new UpdateQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Table name is required');

        $builder->buildRawSqlAndBindings();
    }

    #[Test]
    public function testUpdateValueNotSet(): void
    {
        $builder = new UpdateQueryBuilder();
        $builder->from('a');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Values is required');

        $builder->buildRawSqlAndBindings();
    }
}
