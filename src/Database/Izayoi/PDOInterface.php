<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Izayoi;

use PDO;
use PDOStatement;

/**
 * PDO for mock
 * @package Shibare\Database\Izayoi\PDO
 * @link https://www.php.net/manual/en/class.pdo.php
 */
interface PDOInterface
{
    public function beginTransaction(): bool;

    public function commit(): bool;

    public function errorCode(): ?string;

    /**
     * @return array<array-key, mixed>
     */
    public function errorInfo(): array;

    public function exec(string $statement): int|false;

    public function getAttribute(int $attribute): mixed;

    // public static function getAvailableDrivers(): array;

    public function inTransaction(): bool;

    public function lastInsertId(?string $name = null): string|false;

    /**
     * @param string $query
     * @param array<array-key, mixed> $options
     * @return PDOStatement|PDOStatementInterface|false
     */
    public function prepare(string $query, array $options = []): PDOStatement|PDOStatementInterface|false;

    /**
     * @param string $query
     * @param null|int $fetchMode
     * @param null|int|string $colno_classname_object
     * @param null|array<array-key, mixed> $constructorArgs
     * @return PDOStatement|PDOStatementInterface|false
     */
    public function query(
        string $query,
        ?int $fetchMode = null,
        null|int|string|object $colno_classname_object = null,
        ?array $constructorArgs = null,
    ): PDOStatement|PDOStatementInterface|false;

    public function quote(string $string, int $type = PDO::PARAM_STR): string|false;

    public function rollBack(): bool;

    public function setAttribute(int $attribute, mixed $value): bool;
}
