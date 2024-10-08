<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Stubs;

use Shibare\Database\Izayoi\Attributes\Column;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Izayoi\Attributes\Indexes;
use Shibare\Database\Izayoi\Attributes\PrimaryKeys;
use Shibare\Database\Izayoi\Relations\BelongsTo;
use Shibare\Database\Izayoi\Relations\HasOne;
use Shibare\Database\Schema\ColumnType;

#[Entity(table: 'weapons')]
#[PrimaryKeys(['id'])]
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
        #[Column('id', type: ColumnType::STRING)]
        public readonly Identity $id,
        #[Column('level', type: ColumnType::INTEGER)]
        private int $level,
    ) {}

    public function incrementLevel(int $value = 1): void
    {
        \assert($value > 0, 'incrementLevel must be positive int');
        $this->level += $value;
    }
}
