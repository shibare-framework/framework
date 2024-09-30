<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use Iterator;
use IteratorAggregate;
use PDO;
use stdClass;

/**
 * PDOStatement for mock
 * @extends IteratorAggregate<stdClass>
 * @link https://www.php.net/manual/en/class.pdostatement.php
 */
interface PDOStatementInterface extends IteratorAggregate
{
    public function bindColumn(
        string|int $column,
        mixed &$var,
        int $type = PDO::PARAM_STR,
        int $maxLength = 0,
        mixed $driverOptions = null,
    ): bool;

    public function bindParam(
        string|int $param,
        mixed &$var,
        int $type = PDO::PARAM_STR,
        int $maxLength = 0,
        mixed $driverOptions = null,
    ): bool;

    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR): bool;

    public function closeCursor(): bool;

    public function columnCount(): int;

    public function debugDumpParams(): ?bool;

    public function errorCode(): ?string;

    /**
     * @return array<array-key, mixed>
     */
    public function errorInfo(): array;

    /**
     * @param null|array<array-key, mixed> $params
     * @return bool
     */
    public function execute(?array $params = null): bool;

    /**
     * @param int $mode
     * @param int $cursorOrientation
     * @param int $cursorOffset
     * @return mixed
     */
    public function fetch(
        int $mode = PDO::FETCH_DEFAULT,
        int $cursorOrientation = PDO::FETCH_ORI_NEXT,
        int $cursorOffset = 0,
    ): mixed;

    /**
     * @param int $mode
     * @param null|int|string|callable $column_class_callback
     * @param null|array<array-key ,mixed> $constructorArgs
     * @return array<array-key, mixed>
     */
    public function fetchAll(
        int $mode = PDO::FETCH_COLUMN,
        null|int|string|callable $column_class_callback = null,
        ?array $constructorArgs = null,
    ): array;

    public function fetchColumn(int $column = 0): mixed;

    /**
     * @param null|string $class
     * @param array<array-key, mixed> $constructorArgs
     * @return object|false
     */
    public function fetchObject(?string $class = "stdClass", array $constructorArgs = []): object|false;

    public function getAttribute(int $name): mixed;

    /**
     * @param int $column
     * @return array<array-key, mixed>|false
     */
    public function getColumnMeta(int $column): array|false;

    public function getIterator(): Iterator;

    public function nextRowset(): bool;

    public function rowCount(): int;

    public function setAttribute(int $attribute, mixed $value): bool;

    /**
     * @param int $mode
     * @param null|int|string|object $colno_class_object
     * @param null|array<array-key, mixed> $constructorArgs
     * @return bool
     */
    public function setFetchMode(
        int $mode,
        null|int|string|object $colno_class_object,
        ?array $constructorArgs = null,
    ): bool;
}
