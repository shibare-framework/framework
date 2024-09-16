<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\Database\Tests\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shibare\Database\Izayoi\Attributes\Entity;
use Shibare\Database\Schema\AttributeLocator;

#[CoversClass(AttributeLocator::class)]
final class AttributeLocatorTest extends TestCase
{
    #[Test]
    public function testGetClasses(): void
    {
        include_once __DIR__ . '/../Stubs/Identity.php';
        include_once __DIR__ . '/../Stubs/MasterWeapon.php';
        include_once __DIR__ . '/../Stubs/UserEntity.php';
        include_once __DIR__ . '/../Stubs/WeaponEntity.php';

        $locator = new AttributeLocator();

        $classes = $locator->getClasses(Entity::class);

        self::assertCount(3, $classes);
    }
}
