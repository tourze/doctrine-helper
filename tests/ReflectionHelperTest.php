<?php

namespace Tourze\DoctrineHelper\Tests;

use BizUserBundle\Entity\BizUser;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\ReflectionHelper;

/**
 * @internal
 */
#[CoversClass(ReflectionHelper::class)]
final class ReflectionHelperTest extends TestCase
{
    /**
     * @deprecated 测试已弃用方法的基本功能
     */
    public function testGetClassReflection(): void
    {
        // 由于 getClassReflection 已被标记为过时，这里测试它的基本功能
        // 但建议在新代码中直接使用 new \ReflectionClass()
        $object = new BizUser();
        $reflection = ReflectionHelper::getClassReflection($object);
        $this->assertInstanceOf(\ReflectionClass::class, $reflection);
        $this->assertEquals(BizUser::class, $reflection->getName());

        $reflectionFromString = ReflectionHelper::getClassReflection(BizUser::class);
        $this->assertInstanceOf(\ReflectionClass::class, $reflectionFromString);
        $this->assertEquals(BizUser::class, $reflectionFromString->getName());
    }

    public function testGetReflectionProperty(): void
    {
        $object = new BizUser();
        $property = ReflectionHelper::getReflectionProperty($object, 'id');
        $this->assertInstanceOf(\ReflectionProperty::class, $property);
        $this->assertEquals('id', $property->getName());

        $nonExistentProperty = ReflectionHelper::getReflectionProperty($object, 'nonExistentProperty');
        $this->assertNull($nonExistentProperty);
    }

    public function testGetProperties(): void
    {
        $object = new BizUser();
        $properties = ReflectionHelper::getProperties($object);
        $this->assertArrayHasKey('id', $properties);
        $this->assertArrayHasKey('username', $properties);
        $this->assertArrayHasKey('email', $properties);
    }

    public function testGetMethods(): void
    {
        $object = new BizUser();
        $methods = ReflectionHelper::getMethods($object);
        // 检查是否包含了getId方法
        $methodExists = false;
        foreach ($methods as $method) {
            if ('getId' === $method->getName()) {
                $methodExists = true;
                break;
            }
        }
        $this->assertTrue($methodExists);
    }

    public function testGetParentClasses(): void
    {
        $reflectionClass = new \ReflectionClass(BizUser::class);
        $parentClasses = ReflectionHelper::getParentClasses($reflectionClass);
        $this->assertContains(BizUser::class, $parentClasses);
    }

    public function testHasClassAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(BizUser::class);
        $this->assertTrue(ReflectionHelper::hasClassAttributes($reflectionClass, ORM\Entity::class));

        $reflectionClass = new \ReflectionClass(\stdClass::class);
        $this->assertFalse(ReflectionHelper::hasClassAttributes($reflectionClass, ORM\Entity::class));
    }
}
