<?php

declare(strict_types=1);

/**
 * @license Apache-2.0
 */

namespace Shibare\Database\Schema;

use ReflectionClass;

class AttributeLocator implements AttributeLocatorInterface
{
    public function getClasses(string $attribute_class): array
    {
        $entity_classes = [];

        // TODO: cache
        foreach (\get_declared_classes() as $class) {
            if (!\class_exists($class)) {
                continue;
            }
            $ref = new ReflectionClass($class);
            $attrs = $ref->getAttributes($attribute_class);
            if (\count($attrs) === 1) {
                $entity_classes[] = $class;
            }
        }

        return $entity_classes;
    }
}
