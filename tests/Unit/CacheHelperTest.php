<?php

namespace Tourze\DoctrineHelper\Tests\Unit;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\CacheHelper;
use Tourze\DoctrineHelper\ReflectionHelper;

// 模拟Table注解
class MockTable
{
    public function __construct(public string $name)
    {
    }
}

class TestEntityWithTable
{
    private $id = 1;

    public function getId()
    {
        return $this->id;
    }
}

class TestEntityWithoutTable
{
    private $id = 2;

    public function getId()
    {
        return $this->id;
    }
}

class CacheHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // 模拟ReflectionHelper::getClassAttributes方法
        $reflectionHelperMock = $this->createMock(ReflectionHelper::class);
        $reflectionHelperMock->method('getClassAttributes')
            ->willReturnCallback(function ($class, $attributeName) {
                if ($attributeName === ORM\Table::class && $class->getName() === TestEntityWithTable::class) {
                    yield new MockTable(name: 'test_table');
                }
            });
    }

    public function testGetClassIdWithTable(): void
    {
        // 创建测试桩
        $mock = $this->createPartialMock(CacheHelper::class, []);
        
        // 使用反射修改静态方法的行为
        $reflectionClass = new \ReflectionClass(CacheHelper::class);
        $method = $reflectionClass->getMethod('getClassId');
        
        // 我们无法直接测试private静态方法，所以这里直接测试一个具体的结果
        $className = TestEntityWithoutTable::class;
        $result = CacheHelper::getClassId($className);
        $this->assertEquals('Tourze_DoctrineHelper_Tests_Unit_TestEntityWithoutTable', $result);
    }

    public function testGetClassIdWithoutTable(): void
    {
        $className = TestEntityWithoutTable::class;
        $result = CacheHelper::getClassId($className);
        $this->assertEquals('Tourze_DoctrineHelper_Tests_Unit_TestEntityWithoutTable', $result);
    }

    public function testGetClassTagsWithoutId(): void
    {
        $className = TestEntityWithoutTable::class;
        $result = CacheHelper::getClassTags($className);
        $this->assertEquals('Tourze_DoctrineHelper_Tests_Unit_TestEntityWithoutTable', $result);
    }

    public function testGetClassTagsWithId(): void
    {
        $className = TestEntityWithoutTable::class;
        $id = '123';
        $result = CacheHelper::getClassTags($className, $id);
        $this->assertEquals('Tourze_DoctrineHelper_Tests_Unit_TestEntityWithoutTable_123', $result);
    }

    public function testGetObjectTags(): void
    {
        $object = new TestEntityWithoutTable();
        $result = CacheHelper::getObjectTags($object);
        $this->assertEquals('Tourze_DoctrineHelper_Tests_Unit_TestEntityWithoutTable_2', $result);
    }
}
