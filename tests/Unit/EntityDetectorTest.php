<?php

namespace Tourze\DoctrineHelper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\EntityDetector;
use Tourze\DoctrineHelper\Tests\Unit\Entity\TestEntity;

class NotAnEntity
{
}

class EntityDetectorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIsEntityClassWithEntity(): void
    {
        $this->assertTrue(EntityDetector::isEntityClass(TestEntity::class));
    }

    public function testIsEntityClassWithNonEntity(): void
    {
        $this->assertFalse(EntityDetector::isEntityClass(NotAnEntity::class));
    }

    public function testIsEntityClassWithNonExistingClass(): void
    {
        $this->assertFalse(EntityDetector::isEntityClass('NonExistingClass'));
    }
}
