<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Schema;

enum ColumnType: string
{
    case TINY_INTEGER = 'tinyint';

    case SMALL_INTEGER = 'smallint';

    case INTEGER = 'int';

    case BIG_INTEGER = 'bigint';

    case STRING = 'string';

    case TEXT = 'text';

    case DOUBLE = 'double';

    case FLOAT = 'float';

    case DECIAL = 'decimal';

    case DATETIME = 'datetime';

    case DATE = 'date';

    case TIME = 'time';

    case TIMESTAMP = 'timestamp';

    case BINARY = 'binary';

    case JSON = 'json';
}
