<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\EntityDetector;
use Tourze\DoctrineHelper\Tests\Fixtures\Entity\TestEntityClass;
use Tourze\DoctrineHelper\Tests\Fixtures\Entity\TestEntityClassWithTable;
use Tourze\DoctrineHelper\Tests\Fixtures\Model\TestNonEntityClass;

/**
 * @internal
 */
#[CoversClass(EntityDetector::class)]
class EntityDetectorTest extends TestCase
{
    public function testIsEntityClassWithNonExistentClass(): void
    {
        $className = 'NonExistent\Class\Name';
        $result = EntityDetector::isEntityClass($className);

        $this->assertFalse($result);
    }

    public function testIsEntityClassWithNonEntityClass(): void
    {
        $className = TestNonEntityClass::class;
        $result = EntityDetector::isEntityClass($className);

        $this->assertFalse($result);
    }

    public function testIsEntityClassWithEntityClass(): void
    {
        $className = TestEntityClass::class;
        $result = EntityDetector::isEntityClass($className);

        $this->assertTrue($result);
    }

    public function testIsEntityClassWithEntityClassWithTable(): void
    {
        $className = TestEntityClassWithTable::class;
        $result = EntityDetector::isEntityClass($className);

        $this->assertTrue($result);
    }
}
