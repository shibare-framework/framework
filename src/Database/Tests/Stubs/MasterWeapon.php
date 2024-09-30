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
use Shibare\Database\Schema\ColumnType;

#[Entity(table: 'mst_weapons')]
#[Indexes(columns: ['id', 'level'], unique: true)]
#[PrimaryKeys(['id'])]
class MasterWeapon
{
    /**
     * @param Identity<MasterWeapon> $id
     * @param int $level
     * @param int $attack
     */
    public function __construct(
        #[Column(name: 'id', type: ColumnType::STRING)]
        public readonly Identity $id,
        #[Column(name: 'level', type: ColumnType::INTEGER)]
        public readonly int $level,
        #[Column(name: 'attack', type: ColumnType::INTEGER)]
        public readonly int $attack,
    ) {}
}
