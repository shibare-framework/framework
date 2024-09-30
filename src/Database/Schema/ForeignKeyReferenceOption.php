<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Schema;

enum ForeignKeyReferenceOption: string
{
    case RESTRICT = 'RESTRICT';

    case CASCADE = 'CASCADE';

    case SET_NULL = 'SET NULL';

    case NO_ACTION = 'NO ACTION';

    case SET_DEFAULT = 'SET DEFAULT';
}
