<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Schema;

interface AttributeLocatorInterface
{
    /**
     * Get entity classes
     * @param class-string $attribute_class
     * @return class-string[]
     */
    public function getClasses(string $attribute_class): array;
}
