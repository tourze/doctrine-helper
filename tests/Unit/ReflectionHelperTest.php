<?php

namespace Tourze\DoctrineHelper\Tests\Unit;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\ReflectionHelper;

#[ORM\Entity]
#[ORM\Table(name: 'test_reflection_entity', options: ['comment' => 'Test Reflection Entity'])]
class TestReflectionEntity implements \Stringable
{
    private int $id = 1;
    protected string $name = 'test';
    public array $data = [];

    public function getId(): int
    {
        return $this->id;
    }

    // 添加一个方法，以便测试
    public function someMethod(): void
    {
    }

    public function __toString(): string
    {
        return 'TestReflectionEntity';
    }
}

class ChildTestEntity extends TestReflectionEntity
{
    private string $childProperty = 'child';

    public function getChildProperty(): string
    {
        return $this->childProperty;
    }
}

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY)]
class TestAttribute
{
    public function __construct(public string $value = '')
    {
    }
}

class ClassWithAttributes
{
    #[TestAttribute(value: 'property1')]
    private string $property1;
    
    #[TestAttribute(value: 'property2')]
    private string $property2;

    public function __construct()
    {
        $this->property1 = 'prop1';
        $this->property2 = 'prop2';
    }

    public function getProperty1(): string
    {
        return $this->property1;
    }

    public function getProperty2(): string
    {
        return $this->property2;
    }
}

#[TestAttribute(value: 'class')]
class ClassWithClassAttribute
{
}

class ReflectionHelperTest extends TestCase
{
    public function testGetClassReflection(): void
    {
        $object = new TestReflectionEntity();
        $reflection = ReflectionHelper::getClassReflection($object);
        $this->assertInstanceOf(\ReflectionClass::class, $reflection);
        $this->assertEquals(TestReflectionEntity::class, $reflection->getName());
        
        $reflectionFromString = ReflectionHelper::getClassReflection(TestReflectionEntity::class);
        $this->assertInstanceOf(\ReflectionClass::class, $reflectionFromString);
        $this->assertEquals(TestReflectionEntity::class, $reflectionFromString->getName());
    }
    
    public function testGetReflectionProperty(): void
    {
        $object = new TestReflectionEntity();
        $property = ReflectionHelper::getReflectionProperty($object, 'id');
        $this->assertInstanceOf(\ReflectionProperty::class, $property);
        $this->assertEquals('id', $property->getName());
        
        $nonExistentProperty = ReflectionHelper::getReflectionProperty($object, 'nonExistentProperty');
        $this->assertNull($nonExistentProperty);
    }
    
    public function testGetProperties(): void
    {
        $object = new ChildTestEntity();
        $properties = ReflectionHelper::getProperties($object);
        $this->assertCount(4, $properties);
        $this->assertArrayHasKey('id', $properties);
        $this->assertArrayHasKey('name', $properties);
        $this->assertArrayHasKey('data', $properties);
        $this->assertArrayHasKey('childProperty', $properties);
    }
    
    public function testGetMethods(): void
    {
        $object = new TestReflectionEntity();
        $methods = ReflectionHelper::getMethods($object);
        // 检查是否包含了someMethod方法
        $methodExists = false;
        foreach ($methods as $method) {
            if ($method->getName() === 'someMethod') {
                $methodExists = true;
                break;
            }
        }
        $this->assertTrue($methodExists);
    }
    
    public function testGetParentClasses(): void
    {
        $reflectionClass = new \ReflectionClass(ChildTestEntity::class);
        $parentClasses = ReflectionHelper::getParentClasses($reflectionClass);
        $this->assertContains(ChildTestEntity::class, $parentClasses);
        $this->assertContains(TestReflectionEntity::class, $parentClasses);
    }
    
    public function testGetPropertyAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(ClassWithAttributes::class);
        $attributes = ReflectionHelper::getPropertyAttributes($reflectionClass, TestAttribute::class);
        $this->assertCount(2, $attributes);
        $this->assertInstanceOf(TestAttribute::class, $attributes[0]);
        $this->assertEquals('property1', $attributes[0]->value);
        $this->assertEquals('property2', $attributes[1]->value);
    }
    
    public function testGetPropertyAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(ClassWithAttributes::class);
        $attribute = ReflectionHelper::getPropertyAttribute($reflectionClass, TestAttribute::class);
        $this->assertInstanceOf(TestAttribute::class, $attribute);
        $this->assertEquals('property1', $attribute->value);
    }
    
    public function testGetClassAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(ClassWithClassAttribute::class);
        $attributes = iterator_to_array(ReflectionHelper::getClassAttributes($reflectionClass, TestAttribute::class));
        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(TestAttribute::class, $attributes[0]);
        $this->assertEquals('class', $attributes[0]->value);
    }
    
    public function testHasClassAttributes(): void
    {
        $reflectionClass = new \ReflectionClass(ClassWithClassAttribute::class);
        $this->assertTrue(ReflectionHelper::hasClassAttributes($reflectionClass, TestAttribute::class));
        
        $reflectionClass = new \ReflectionClass(TestReflectionEntity::class);
        $this->assertTrue(ReflectionHelper::hasClassAttributes($reflectionClass, ORM\Entity::class));
        
        $reflectionClass = new \ReflectionClass(ClassWithAttributes::class);
        $this->assertFalse(ReflectionHelper::hasClassAttributes($reflectionClass, TestAttribute::class));
    }
}
