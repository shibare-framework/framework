<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Izayoi\QueryBuilder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Database\Izayoi\QueryBuilder\InsertQueryBuilder;

#[CoversClass(InsertQueryBuilder::class)]
final class InsertQueryBuilderTest extends TestCase
{
    #[Test]
    public function testInsert(): void
    {
        $builder = new InsertQueryBuilder();
        $expected = $builder->into('a')->value(['b' => 1])->buildRawSqlAndBindings();

        self::assertSame('INSERT INTO `a` (`b`) VALUES (?)', $expected['sql']);
        self::assertSame([1], $expected['bindings']);
    }

    #[Test]
    public function testInsertList(): void
    {
        $builder = new InsertQueryBuilder();
        $expected = $builder->into('a')->valueList([['b' => 1], ['b' => 2]])->buildRawSqlAndBindings();

        self::assertSame('INSERT INTO `a` (`b`) VALUES (?), (?)', $expected['sql']);
        self::assertSame([1, 2], $expected['bindings']);
    }

    #[Test]
    public function testInsertNotSet(): void
    {
        $builder = new InsertQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Table name is required');

        $builder->buildRawSqlAndBindings();
    }

    #[Test]
    public function testInsertValueNotSet(): void
    {
        $builder = new InsertQueryBuilder();
        $builder->into('a');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Values is required');

        $builder->buildRawSqlAndBindings();
    }

    #[Test]
    public function testInsertValueListNotSet(): void
    {
        $builder = new InsertQueryBuilder();
        $builder->into('a')->valueList([['B' => 1], ['C' => 2]]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Column names must be same');

        $builder->buildRawSqlAndBindings();
    }

    #[Test]
    public function testValueIsNotAssoc(): void
    {
        $builder = new InsertQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Record must be associative array');

        $builder->into('a')->value([1, 2]);
    }

    #[Test]
    public function testValueListIsNotList(): void
    {
        $builder = new InsertQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Records must be list of associative array');

        $builder->into('a')->valueList(['b' => 1]);
    }
}
