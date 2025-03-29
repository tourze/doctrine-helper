<?php

namespace Tourze\DoctrineHelper;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;

class CacheHelper
{
    /**
     * 获取指定类或id所有可能的标签
     */
    public static function getClassId(string $className): string
    {
        // 优先返回表名
        $refTables = ReflectionHelper::getClassAttributes(ReflectionHelper::getClassReflection($className), ORM\Table::class);
        foreach ($refTables as $refTable) {
            return $refTable->name;
        }

        $className = str_replace('\\', '_', $className);
        $className = trim($className, '_');
        return trim($className, '\\');
    }

    /**
     * 获取指定类或id所有可能的标签
     */
    public static function getClassTags(string $className, ?string $id = null): string
    {
        $className = static::getClassId($className);

        if ($id !== null) {
            return "{$className}_{$id}";
        }
        return $className;
    }

    /**
     * 获取单个对象对应的缓存标签
     */
    public static function getObjectTags(object $object): string
    {
        $className = get_class($object);
        $className = ClassUtils::getRealClass($className);
        $id = $object->getId();

        return self::getClassTags($className, $id);
    }
}
