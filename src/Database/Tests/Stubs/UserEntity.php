<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Stubs;

use SensitiveParameter;
use Shibare\Database\Izayoi\Attributes\Column;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Izayoi\Attributes\PrimaryKeys;
use Shibare\Database\Izayoi\Relations\HasMany;
use Shibare\Database\Schema\ColumnType;

#[Entity(table: 'users')]
#[PrimaryKeys(['id'])]
class UserEntity
{
    /**
     * @var list<WeaponEntity> $weapons
     */
    #[HasMany(WeaponEntity::class)]
    public array $weapons = [];

    /**
     * @param Identity<UserEntity> $id
     * @param non-empty-string $user_name
     * @param non-empty-string $token
     */
    public function __construct(
        #[Column('id', type: ColumnType::STRING)]
        public readonly Identity $id,
        #[Column('user_name', ColumnType::STRING)]
        private string $user_name,
        #[Column('token', ColumnType::STRING)]
        #[SensitiveParameter]
        private string $token,
    ) {}

    /**
     * @param non-empty-string $name
     * @return void
     */
    public function updateUserName(string $name): void
    {
        $this->user_name = $name;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function generateNewToken(string $password): void
    {
        $this->token = \password_hash($password, \PASSWORD_DEFAULT);
    }
}
