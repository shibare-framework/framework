<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Izayoi\QueryBuilder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Database\Izayoi\QueryBuilder\DeleteQueryBuilder;

#[CoversClass(DeleteQueryBuilder::class)]
final class DeleteQueryBuilderTest extends TestCase
{
    #[Test]
    public function testDelete(): void
    {
        $builder = new DeleteQueryBuilder();
        $expected = $builder->from('a')->whereEquals('id', 1)->buildRawSqlAndBindings();

        self::assertSame('DELETE FROM `a` WHERE `id` = ?', $expected['sql']);
        self::assertSame([1], $expected['bindings']);
    }

    #[Test]
    public function testUpdateNotSet(): void
    {
        $builder = new DeleteQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Table name is required');

        $builder->buildRawSqlAndBindings();
    }
}
