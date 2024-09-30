<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\QueryBuilder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Database\QueryBuilder\GroupByQueryBuilderTrait;
use Shibare\Database\QueryBuilder\JoinQueryBuilderTrait;
use Shibare\Database\QueryBuilder\OrderByQueryBuilderTrait;
use Shibare\Database\QueryBuilder\SelectQueryBuilder;
use Shibare\Database\QueryBuilder\SelectQueryBuilderTrait;
use Shibare\Database\QueryBuilder\WhereQueryBuilderTrait;

#[CoversClass(SelectQueryBuilder::class)]
#[CoversTrait(GroupByQueryBuilderTrait::class)]
#[CoversTrait(JoinQueryBuilderTrait::class)]
#[CoversTrait(OrderByQueryBuilderTrait::class)]
#[CoversTrait(SelectQueryBuilderTrait::class)]
#[CoversTrait(WhereQueryBuilderTrait::class)]
final class SelectQueryBuilderTest extends TestCase
{
    #[Test]
    public function testBaseQuery(): void
    {
        $builder = new SelectQueryBuilder();

        $expected = $builder->from('a')->buildRawSqlAndBindings();

        self::assertSame('SELECT * FROM `a`', $expected['sql']);
        self::assertSame([], $expected['bindings']);
    }

    #[Test]
    public function testFromNotSet(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Table name is required');

        $builder->buildRawSqlAndBindings();
    }

    #[Test]
    public function testUnion(): void
    {
        $builder1 = new SelectQueryBuilder();
        $builder1->from('a')->whereIn('b', [1, 2]);

        $builder2 = new SelectQueryBuilder();
        $builder2->from('c')->whereIn('d', [3, 4]);

        $expected = $builder1->union($builder2);

        self::assertSame('SELECT * FROM `a` WHERE `b` IN (?, ?) UNION SELECT * FROM `c` WHERE `d` IN (?, ?)', $expected['sql']);
        self::assertSame([1, 2, 3, 4], $expected['bindings']);

        $builder3 = new SelectQueryBuilder();
        $builder3->from('e')->whereEquals('f', 5);

        $builder4 = new SelectQueryBuilder();
        $builder4->from('g')->whereEquals('h', 6);

        $expected = $builder1->union([$builder2, $builder3, $builder4]);

        self::assertSame('SELECT * FROM `a` WHERE `b` IN (?, ?) UNION SELECT * FROM `c` WHERE `d` IN (?, ?) UNION SELECT * FROM `e` WHERE `f` = ? UNION SELECT * FROM `g` WHERE `h` = ?', $expected['sql']);
        self::assertSame([1, 2, 3, 4, 5, 6], $expected['bindings']);
    }

    #[Test]
    public function testSelectWhere(): void
    {
        $builder = new SelectQueryBuilder();

        $builder->from('a')
            ->select(['b', 'c'])
            ->whereEquals('d', 1)
            ->whereBetween('e', 2, 3)
            ->whereIn('f', [4, 5])
            ->whereIsNotNull('g')
            ->whereIsNull('h')
            ->whereLike('i', 'j')
            ->whereNotBetween('k', 6, 7)
            ->whereNotEquals('l', 8)
            ->whereNotIn('m', [9, 10])
            ->whereNotLike('n', 'o')
            ->buildRawSqlAndBindings();

        $expected = $builder->buildRawSqlAndBindings();

        self::assertSame('SELECT `b`, `c` FROM `a` WHERE `d` = ? AND `e` BETWEEN ? AND ? AND `f` IN (?, ?) AND `g` IS NOT NULL AND `h` IS NULL AND `i` LIKE ? AND `k` NOT BETWEEN ? AND ? AND `l` <> ? AND `m` NOT IN (?, ?) AND `n` NOT LIKE ?', $expected['sql']);
        self::assertSame([1, 2, 3, 4, 5, 'j', 6, 7, 8, 9, 10, 'o'], $expected['bindings']);
    }

    #[Test]
    public function testSelectOrWhere(): void
    {
        $builder = new SelectQueryBuilder();

        $builder->from('a')
            ->select(['b', 'c'])
            ->orWhereEquals('d', 1)
            ->orWhereBetween('e', 2, 3)
            ->orWhereIn('f', [4, 5])
            ->orWhereIsNotNull('g')
            ->orWhereIsNull('h')
            ->orWhereLike('i', 'j')
            ->orWhereNotBetween('k', 6, 7)
            ->orWhereNotEquals('l', 8)
            ->orWhereNotIn('m', [9, 10])
            ->orWhereNotLike('n', 'o')
            ->buildRawSqlAndBindings();

        $expected = $builder->buildRawSqlAndBindings();

        self::assertSame('SELECT `b`, `c` FROM `a` WHERE `d` = ? OR `e` BETWEEN ? AND ? OR `f` IN (?, ?) OR `g` IS NOT NULL OR `h` IS NULL OR `i` LIKE ? OR `k` NOT BETWEEN ? AND ? OR `l` <> ? OR `m` NOT IN (?, ?) OR `n` NOT LIKE ?', $expected['sql']);
        self::assertSame([1, 2, 3, 4, 5, 'j', 6, 7, 8, 9, 10, 'o'], $expected['bindings']);
    }

    #[Test]
    public function testWhereInvalid(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('where operator is invalid operator, got "invalid"');

        $builder->where('b', 'invalid', 1);
    }

    #[Test]
    public function testOrWhereInvalid(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('where operator is invalid operator, got "invalid"');

        $builder->orWhere('b', 'invalid', 1);
    }

    #[Test]
    public function testWhereInEmpty(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('values of whereIn query must not be empty');

        $builder->whereIn('b', []);
    }

    #[Test]
    public function testWhereNotInEmpty(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('values of whereNotIn query must not be empty');

        $builder->whereNotIn('b', []);
    }

    #[Test]
    public function testOrWhereInEmpty(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('values of whereIn query must not be empty');

        $builder->orWhereIn('b', []);
    }

    #[Test]
    public function testOrWhereNotInEmpty(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('values of whereNotIn query must not be empty');

        $builder->orWhereNotIn('b', []);
    }

    #[Test]
    public function testGroupBy(): void
    {
        $builder = new SelectQueryBuilder();

        $builder->from('a')
            ->groupBy('b')
            ->groupBy(['c', 'd']);

        $expected = $builder->buildRawSqlAndBindings();

        self::assertSame('SELECT * FROM `a` GROUP BY `b`, `c`, `d`', $expected['sql']);
        self::assertSame([], $expected['bindings']);
    }

    #[Test]
    public function testOrderBy(): void
    {
        $builder = new SelectQueryBuilder();

        $builder->from('a')
            ->orderByAsc('b')
            ->orderByAsc(['c', 'd'])
            ->orderByDesc('e')
            ->orderByDesc(['f', 'g']);

        $expected = $builder->buildRawSqlAndBindings();

        self::assertSame('SELECT * FROM `a` ORDER BY `b` ASC, `c` ASC, `d` ASC, `e` DESC, `f` DESC, `g` DESC', $expected['sql']);
        self::assertSame([], $expected['bindings']);
    }

    #[Test]
    public function testOrderByEmpty(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('orderBy method cannot be empty');

        $builder->orderBy([]);
    }

    #[Test]
    public function testOrderByEmptyColumnName(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('orderBy method requires column name');

        $builder->orderBy([['', 'ASC']]);
    }

    #[Test]
    public function testOrderByInvalid(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('orderBy method allows only ASC or DESC, got "invalid"');

        $builder->orderBy([['a', 'invalid']]);
    }

    #[Test]
    public function testJoin(): void
    {
        $builder = new SelectQueryBuilder();

        $builder->from('a')
            ->innerJoin('b', 'c', 'd')
            ->leftOuterJoin('e', 'f', 'g')
            ->rightOuterJoin('h', 'i', 'j')
            ->fullOuterJoin('k', 'l', 'm');

        $expected = $builder->buildRawSqlAndBindings();

        self::assertSame('SELECT * FROM `a` INNER JOIN `b` ON `a`.`c` = `b`.`d` LEFT OUTER JOIN `e` ON `a`.`f` = `e`.`g` RIGHT OUTER JOIN `h` ON `a`.`i` = `h`.`j` FULL OUTER JOIN `k` ON `a`.`l` = `k`.`m`', $expected['sql']);
        self::assertSame([], $expected['bindings']);
    }

    #[Test]
    public function testQuoteColumnNameInvalid(): void
    {
        $builder = new SelectQueryBuilder();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Column cannot contain more than one dot "a.b.c"');

        $builder->from('a')->select(['a.b.c']);
    }

    #[Test]
    public function testQuoteColumnName(): void
    {
        $builder = new SelectQueryBuilder();

        $expected = $builder->from('a')->select(['a.b'])->buildRawSqlAndBindings();

        self::assertSame('SELECT `a`.`b` FROM `a`', $expected['sql']);
        self::assertSame([], $expected['bindings']);
    }

    #[Test]
    public function testSelectRaw(): void
    {
        $builder = new SelectQueryBuilder();

        $expected = $builder->from('a')->selectRaw(['MAX(b)'])->buildRawSqlAndBindings();

        self::assertSame('SELECT MAX(b) FROM `a`', $expected['sql']);
        self::assertSame([], $expected['bindings']);
    }
}
