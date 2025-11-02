<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\ReflectionHelper;

class ReflectionHelperTest extends TestCase
{
    public function testGetClassReflectionWithString(): void
    {
        $reflectionClass = ReflectionHelper::getClassReflection(TestReflectionClass::class);

        $this->assertInstanceOf(\ReflectionClass::class, $reflectionClass);
        $this->assertSame(TestReflectionClass::class, $reflectionClass->getName());
    }

    public function testGetClassReflectionWithObject(): void
    {
        $object = new TestReflectionClass();
        $reflectionClass = ReflectionHelper::getClassReflection($object);

        $this->assertInstanceOf(\ReflectionClass::class, $reflectionClass);
        $this->assertSame(TestReflectionClass::class, $reflectionClass->getName());
    }

    public function testGetClassReflectionWithNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Class 'NonExistent\\Class\\Name' does not exist");

        ReflectionHelper::getClassReflection('NonExistent\\Class\\Name');
    }

    public function testGetReflectionPropertyWithExistingProperty(): void
    {
        $object = new TestReflectionClass();
        $property = ReflectionHelper::getReflectionProperty($object, 'publicProperty');

        $this->assertInstanceOf(\ReflectionProperty::class, $property);
        $this->assertSame('publicProperty', $property->getName());
    }

    public function testGetReflectionPropertyWithNonExistentProperty(): void
    {
        $object = new TestReflectionClass();
        $property = ReflectionHelper::getReflectionProperty($object, 'nonExistentProperty');

        $this->assertNull($property);
    }

    public function testGetPropertiesWithObjectDefaultFilter(): void
    {
        $object = new TestReflectionClass();
        $properties = ReflectionHelper::getProperties($object);

        $this->assertIsArray($properties);
        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayHasKey('protectedProperty', $properties);
        $this->assertArrayHasKey('privateProperty', $properties);
    }

    public function testGetPropertiesWithStringClassName(): void
    {
        $properties = ReflectionHelper::getProperties(TestReflectionClass::class);

        $this->assertIsArray($properties);
        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayHasKey('protectedProperty', $properties);
        $this->assertArrayHasKey('privateProperty', $properties);
    }

    public function testGetPropertiesWithPublicFilter(): void
    {
        $properties = ReflectionHelper::getProperties(TestReflectionClass::class, \ReflectionProperty::IS_PUBLIC);

        $this->assertIsArray($properties);
        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayNotHasKey('protectedProperty', $properties);
        $this->assertArrayNotHasKey('privateProperty', $properties);
    }

    public function testGetPropertiesWithNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Class 'NonExistent\\Class\\Name' does not exist");

        ReflectionHelper::getProperties('NonExistent\\Class\\Name');
    }

    public function testGetMethodsWithDefaultFilter(): void
    {
        $methods = ReflectionHelper::getMethods(TestReflectionClass::class);

        $this->assertIsArray($methods);
        $this->assertNotEmpty($methods);

        $methodNames = array_map(fn(\ReflectionMethod $method) => $method->getName(), $methods);
        $this->assertContains('publicMethod', $methodNames);
        $this->assertContains('protectedMethod', $methodNames);
        $this->assertContains('privateMethod', $methodNames);
    }

    public function testGetMethodsWithPublicFilter(): void
    {
        $methods = ReflectionHelper::getMethods(TestReflectionClass::class, \ReflectionMethod::IS_PUBLIC);

        $this->assertIsArray($methods);
        $methodNames = array_map(fn(\ReflectionMethod $method) => $method->getName(), $methods);
        $this->assertContains('publicMethod', $methodNames);
        $this->assertNotContains('protectedMethod', $methodNames);
        $this->assertNotContains('privateMethod', $methodNames);
    }

    public function testGetMethodsWithNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Class 'NonExistent\\Class\\Name' does not exist");

        ReflectionHelper::getMethods('NonExistent\\Class\\Name');
    }

    public function testGetParentClasses(): void
    {
        $reflectionClass = new \ReflectionClass(TestChildClass::class);
        $parentClasses = ReflectionHelper::getParentClasses($reflectionClass);

        $this->assertIsArray($parentClasses);
        $this->assertContains(TestChildClass::class, $parentClasses);
        $this->assertContains(TestReflectionClass::class, $parentClasses);
        $this->assertContains(TestInterface::class, $parentClasses);
    }

    public function testGetPropertyAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(TestEntityWithAttributes::class);
        $attributes = ReflectionHelper::getPropertyAttributes($reflectionClass, ORM\Column::class);

        $this->assertIsArray($attributes);
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

// Test helper classes

interface TestInterface
{
}

class TestReflectionClass implements TestInterface
{
    public string $publicProperty = 'public';
    protected string $protectedProperty = 'protected';
    private string $privateProperty = 'private';

    public function publicMethod(): string
    {
        return 'public method';
    }

    protected function protectedMethod(): string
    {
        return 'protected method';
    }

    private function privateMethod(): string
    {
        return 'private method';
    }
}

class TestChildClass extends TestReflectionClass
{
    public string $childProperty = 'child';
}

#[ORM\Entity]
#[ORM\Table(name: 'test_entity_with_attributes')]
class TestEntityWithAttributes
{
    #[ORM\Column(type: 'string')]
    public string $name = '';

    #[ORM\Column(type: 'integer')]
    public int $age = 0;

    public string $noAttribute = '';
}