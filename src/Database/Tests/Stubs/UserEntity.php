<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Stubs;

use Shibare\Database\Izayoi\Attributes\Column;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Izayoi\Attributes\PrimaryKey;
use Shibare\Database\Izayoi\Relations\HasMany;

#[Entity(table: 'users')]
class UserEntity
{
    /**
     * @var list<WeaponEntity> $weapons
     */
    #[HasMany(WeaponEntity::class)]
    public array $weapons = [];

    /**
     * @param Identity<UserEntity> $id
     * @param string $user_name
     * @param string $token
     */
    public function __construct(
        #[PrimaryKey]
        public readonly Identity $id,
        #[Column]
        private string $user_name,
        #[Column]
        private string $token,
    ) {}

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
