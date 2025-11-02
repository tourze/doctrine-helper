<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\EntityDetector;

class EntityDetectorTest extends TestCase
{
    public function testIsEntityClassWithNonExistentClass(): void
    {
        $className = 'NonExistent\\Class\\Name';
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

// Test helper classes

class TestNonEntityClass
{
}

#[ORM\Entity]
class TestEntityClass
{
}

#[ORM\Entity]
#[ORM\Table(name: 'test_entity_table')]
class TestEntityClassWithTable
{
}