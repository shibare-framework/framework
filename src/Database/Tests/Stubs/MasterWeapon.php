<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Tests\Stubs;

use Shibare\Database\Izayoi\Attributes\Column;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Izayoi\Attributes\PrimaryKey;
use Shibare\Database\Izayoi\Attributes\UniqueIndexes;

#[Entity(table: 'mst_weapons')]
#[UniqueIndexes(['id', 'level'])]
class MasterWeapon
{
    /**
     * @param Identity<MasterWeapon> $id
     * @param int $level
     * @param int $attack
     */
    public function __construct(
        #[PrimaryKey]
        public readonly Identity $id,
        #[Column]
        public readonly int $level,
        #[Column]
        public readonly int $attack,
    ) {}
}
