<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\ReflectionHelper;
use Tourze\DoctrineHelper\Tests\Fixtures\Entity\TestEntityWithAttributes;
use Tourze\DoctrineHelper\Tests\Fixtures\Reflection\TestChildClass;
use Tourze\DoctrineHelper\Tests\Fixtures\Reflection\TestConcreteReflectionClass;
use Tourze\DoctrineHelper\Tests\Fixtures\Reflection\TestInterface;
use Tourze\DoctrineHelper\Tests\Fixtures\Reflection\TestReflectionClass;

/**
 * @internal
 */
#[CoversClass(ReflectionHelper::class)]
class ReflectionHelperTest extends TestCase
{
    public function testGetClassReflectionWithString(): void
    {
        // @phpstan-ignore-next-line 测试覆盖已废弃方法，按需保留
        $reflectionClass = ReflectionHelper::getClassReflection(TestReflectionClass::class);

        $this->assertInstanceOf(\ReflectionClass::class, $reflectionClass);
        $this->assertSame(TestReflectionClass::class, $reflectionClass->getName());
    }

    public function testGetClassReflectionWithObject(): void
    {
        $object = new TestConcreteReflectionClass();
        // @phpstan-ignore-next-line 测试覆盖已废弃方法，按需保留
        $reflectionClass = ReflectionHelper::getClassReflection($object);

        $this->assertInstanceOf(\ReflectionClass::class, $reflectionClass);
        $this->assertSame(TestConcreteReflectionClass::class, $reflectionClass->getName());
    }

    public function testGetClassReflectionWithNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Class 'NonExistent\\Class\\Name' does not exist");
        // @phpstan-ignore-next-line 测试覆盖已废弃方法，验证异常
        ReflectionHelper::getClassReflection('NonExistent\Class\Name');
    }

    public function testGetReflectionPropertyWithExistingProperty(): void
    {
        $object = new TestConcreteReflectionClass();
        $property = ReflectionHelper::getReflectionProperty($object, 'publicProperty');

        $this->assertInstanceOf(\ReflectionProperty::class, $property);
        $this->assertSame('publicProperty', $property->getName());
    }

    public function testGetReflectionPropertyWithNonExistentProperty(): void
    {
        $object = new TestConcreteReflectionClass();
        $property = ReflectionHelper::getReflectionProperty($object, 'nonExistentProperty');

        $this->assertNull($property);
    }

    public function testGetPropertiesWithObjectDefaultFilter(): void
    {
        $object = new TestConcreteReflectionClass();
        $properties = ReflectionHelper::getProperties($object);

        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayHasKey('protectedProperty', $properties);
        $this->assertArrayHasKey('privateProperty', $properties);
    }

    public function testGetPropertiesWithStringClassName(): void
    {
        $properties = ReflectionHelper::getProperties(TestReflectionClass::class);

        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayHasKey('protectedProperty', $properties);
        $this->assertArrayHasKey('privateProperty', $properties);
    }

    public function testGetPropertiesWithPublicFilter(): void
    {
        $properties = ReflectionHelper::getProperties(TestReflectionClass::class, \ReflectionProperty::IS_PUBLIC);

        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayNotHasKey('protectedProperty', $properties);
        $this->assertArrayNotHasKey('privateProperty', $properties);
    }

    public function testGetPropertiesWithNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Class 'NonExistent\\Class\\Name' does not exist");

        ReflectionHelper::getProperties('NonExistent\Class\Name');
    }

    public function testGetMethodsWithDefaultFilter(): void
    {
        $methods = ReflectionHelper::getMethods(TestReflectionClass::class);

        $this->assertNotEmpty($methods);

        $methodNames = array_map(fn (\ReflectionMethod $method) => $method->getName(), $methods);
        $this->assertContains('publicMethod', $methodNames);
        $this->assertContains('protectedMethod', $methodNames);
        $this->assertContains('getPrivateProperty', $methodNames);
    }

    public function testGetMethodsWithPublicFilter(): void
    {
        $methods = ReflectionHelper::getMethods(TestReflectionClass::class, \ReflectionMethod::IS_PUBLIC);

        $methodNames = array_map(fn (\ReflectionMethod $method) => $method->getName(), $methods);
        $this->assertContains('publicMethod', $methodNames);
        $this->assertNotContains('protectedMethod', $methodNames);
        $this->assertContains('getPrivateProperty', $methodNames);
    }

    public function testGetMethodsWithNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Class 'NonExistent\\Class\\Name' does not exist");

        ReflectionHelper::getMethods('NonExistent\Class\Name');
    }

    public function testGetParentClasses(): void
    {
        $reflectionClass = new \ReflectionClass(TestChildClass::class);
        $parentClasses = ReflectionHelper::getParentClasses($reflectionClass);

        $this->assertContains(TestChildClass::class, $parentClasses);
        $this->assertContains(TestReflectionClass::class, $parentClasses);
        $this->assertContains(TestInterface::class, $parentClasses);
    }

    public function testGetPropertyAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(TestEntityWithAttributes::class);
        $attributes = ReflectionHelper::getPropertyAttributes($reflectionClass, ORM\Column::class);

        $this->assertNotEmpty($attributes);
        $this->assertInstanceOf(ORM\Column::class, $attributes[0]);
    }

    public function testGetPropertyAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(TestEntityWithAttributes::class);
        $attribute = ReflectionHelper::getPropertyAttribute($reflectionClass, ORM\Column::class);

        $this->assertInstanceOf(ORM\Column::class, $attribute);
    }

    public function testGetPropertyAttributeWithNoAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(TestReflectionClass::class);
        $attribute = ReflectionHelper::getPropertyAttribute($reflectionClass, ORM\Column::class);

        $this->assertNull($attribute);
    }

    public function testGetClassAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(TestEntityWithAttributes::class);
        $attributes = ReflectionHelper::getClassAttributes($reflectionClass, ORM\Entity::class);

        $this->assertInstanceOf(\Traversable::class, $attributes);
        $attributeArray = iterator_to_array($attributes);
        $this->assertNotEmpty($attributeArray);
        $this->assertInstanceOf(ORM\Entity::class, $attributeArray[0]);
    }

    public function testHasClassAttributesWithExistingAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(TestEntityWithAttributes::class);
        $hasAttribute = ReflectionHelper::hasClassAttributes($reflectionClass, ORM\Entity::class);

        $this->assertTrue($hasAttribute);
    }

    public function testHasClassAttributesWithNonExistingAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(TestReflectionClass::class);
        $hasAttribute = ReflectionHelper::hasClassAttributes($reflectionClass, ORM\Entity::class);

        $this->assertFalse($hasAttribute);
    }
}
