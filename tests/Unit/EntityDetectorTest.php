<?php

namespace Tourze\DoctrineHelper\Tests\Unit;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\EntityDetector;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity', options: ['comment' => 'Test Entity'])]
class TestEntity implements \Stringable
{
    public function __toString(): string
    {
        return 'TestEntity';
    }
}

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
        $this->markTestSkipped('由于依赖注解类，这个测试暂时跳过');
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
