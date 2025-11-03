<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\CacheHelper;
use Tourze\DoctrineHelper\Tests\Fixtures\Entity\TestEntityWithTable;
use Tourze\DoctrineHelper\Tests\Fixtures\Model\TestEntityWithId;
use Tourze\DoctrineHelper\Tests\Fixtures\Model\TestEntityWithNonScalarId;
use Tourze\DoctrineHelper\Tests\Fixtures\Model\TestEntityWithoutGetId;
use Tourze\DoctrineHelper\Tests\Fixtures\Model\TestEntityWithoutTable;

/**
 * @internal
 */
#[CoversClass(CacheHelper::class)]
class CacheHelperTest extends TestCase
{
    public function testGetClassIdWithNonExistentClass(): void
    {
        $className = 'NonExistent\Class\Name';
        $result = CacheHelper::getClassId($className);

        $expected = 'NonExistent_Class_Name';
        $this->assertSame($expected, $result);
    }

    public function testGetClassIdWithClassWithoutTable(): void
    {
        $className = TestEntityWithoutTable::class;
        $result = CacheHelper::getClassId($className);

        $expected = 'Tourze_DoctrineHelper_Tests_Fixtures_Model_TestEntityWithoutTable';
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

        $expected = 'Tourze_DoctrineHelper_Tests_Fixtures_Model_TestEntityWithId_456';
        $this->assertSame($expected, $result);
    }

    public function testGetObjectTagsWithNullId(): void
    {
        $object = new TestEntityWithId();
        // ID is null by default

        $result = CacheHelper::getObjectTags($object);

        $expected = 'Tourze_DoctrineHelper_Tests_Fixtures_Model_TestEntityWithId';
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
