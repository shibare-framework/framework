<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Stubs;

use Shibare\Database\Izayoi\Attributes\Column;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Izayoi\Attributes\Indexes;
use Shibare\Database\Izayoi\Attributes\PrimaryKey;
use Shibare\Database\Izayoi\Relations\BelongsTo;
use Shibare\Database\Izayoi\Relations\HasOne;

#[Entity(table: 'weapons')]
#[Indexes(['user_id'])]
class WeaponEntity
{
    #[HasOne(MasterWeapon::class)]
    public ?MasterWeapon $master;

    #[BelongsTo(UserEntity::class)]
    public ?UserEntity $user;

    /**
     * @param Identity<WeaponEntity> $id
     * @param int $level
     */
    public function __construct(
        #[PrimaryKey]
        public readonly Identity $id,
        #[Column]
        private int $level,
    ) {}

    public function incrementLevel(int $value = 1): void
    {
        \assert($value > 0, 'incrementLevel must be positive int');
        $this->level += $value;
    }
}
