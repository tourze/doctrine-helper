<?php

namespace Tourze\DoctrineHelper\Tests;

use BizUserBundle\Entity\BizUser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\EntityDetector;

/**
 * @internal
 */
#[CoversClass(EntityDetector::class)]
final class EntityDetectorTest extends TestCase
{
    public function testIsEntityClassWithEntity(): void
    {
        $this->assertTrue(EntityDetector::isEntityClass(BizUser::class));
    }

    public function testIsEntityClassWithNonEntity(): void
    {
        $this->assertFalse(EntityDetector::isEntityClass(\stdClass::class));
    }

    public function testIsEntityClassWithNonExistingClass(): void
    {
        $this->assertFalse(EntityDetector::isEntityClass('NonExistingClass'));
    }
}
