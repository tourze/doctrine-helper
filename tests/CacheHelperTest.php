<?php

namespace Tourze\DoctrineHelper\Tests;

use BizUserBundle\Entity\BizUser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\CacheHelper;

/**
 * @internal
 */
#[CoversClass(CacheHelper::class)]
final class CacheHelperTest extends TestCase
{
    public function testGetClassIdWithTable(): void
    {
        // 测试带有 Table 注解的实体类，应该返回表名
        $className = BizUser::class;
        $result = CacheHelper::getClassId($className);
        $this->assertEquals('biz_user', $result);
    }

    public function testGetClassIdWithoutTable(): void
    {
        // 测试带有 Table 注解的实体类，应该返回表名（不是类名）
        $className = BizUser::class;
        $result = CacheHelper::getClassId($className);
        $this->assertEquals('biz_user', $result);
    }

    public function testGetClassTagsWithoutId(): void
    {
        $className = BizUser::class;
        $result = CacheHelper::getClassTags($className);
        $this->assertEquals('biz_user', $result);
    }

    public function testGetClassTagsWithId(): void
    {
        $className = BizUser::class;
        $id = '123';
        $result = CacheHelper::getClassTags($className, $id);
        $this->assertEquals('biz_user_123', $result);
    }

    public function testGetObjectTags(): void
    {
        $object = new BizUser();
        $object->setId(2);
        $result = CacheHelper::getObjectTags($object);
        $this->assertEquals('biz_user_2', $result);
    }
}
