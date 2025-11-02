<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\CacheHelper;

class CacheHelperTest extends TestCase
{
    public function testGetClassIdWithNonExistentClass(): void
    {
        $className = 'NonExistent\\Class\\Name';
        $result = CacheHelper::getClassId($className);

        $expected = 'NonExistent_Class_Name';
        $this->assertSame($expected, $result);
    }

    public function testGetClassIdWithClassWithoutTable(): void
    {
        $className = TestEntityWithoutTable::class;
        $result = CacheHelper::getClassId($className);

        $expected = 'Tourze_DoctrineHelper_Tests_TestEntityWithoutTable';
        $this->assertSame($expected, $result);
    }

    public function testGetClassIdWithClassWithTable(): void
    {
        $className = TestEntityWithTable::class;
        $result = CacheHelper::getClassId($className);

        $expected = 'test_table';
        $this->assertSame($expected, $result);
    }

    public function testGetClassTagsWithoutId(): void
    {
        $className = TestEntityWithTable::class;
        $result = CacheHelper::getClassTags($className);

        $expected = 'test_table';
        $this->assertSame($expected, $result);
    }

    public function testGetClassTagsWithId(): void
    {
        $className = TestEntityWithTable::class;
        $id = '123';
        $result = CacheHelper::getClassTags($className, $id);

        $expected = 'test_table_123';
        $this->assertSame($expected, $result);
    }

    public function testGetObjectTagsWithValidObject(): void
    {
        $object = new TestEntityWithId();
        $object->setId(456);

        $result = CacheHelper::getObjectTags($object);

        $expected = 'Tourze_DoctrineHelper_Tests_TestEntityWithId_456';
        $this->assertSame($expected, $result);
    }

    public function testGetObjectTagsWithNullId(): void
    {
        $object = new TestEntityWithId();
        // ID is null by default

        $result = CacheHelper::getObjectTags($object);

        $expected = 'Tourze_DoctrineHelper_Tests_TestEntityWithId';
        $this->assertSame($expected, $result);
    }

    public function testGetObjectTagsWithoutGetIdMethod(): void
    {
        $object = new TestEntityWithoutGetId();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Object must have getId() method');

        CacheHelper::getObjectTags($object);
    }

    public function testGetObjectTagsWithNonScalarId(): void
    {
        $object = new TestEntityWithNonScalarId();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Entity ID must be scalar');

        CacheHelper::getObjectTags($object);
    }
}

// Test helper classes

class TestEntityWithoutTable
{
}

#[ORM\Entity]
#[ORM\Table(name: 'test_table')]
class TestEntityWithTable
{
}

class TestEntityWithId
{
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}

class TestEntityWithoutGetId
{
}

class TestEntityWithNonScalarId
{
    public function getId(): array
    {
        return ['complex' => 'id'];
    }
}