<?php

declare(strict_types=1);

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
        if (!class_exists($className)) {
            return self::normalizeClassName($className);
        }

        $reflectionClass = new \ReflectionClass($className);
        $refTables = ReflectionHelper::getClassAttributes($reflectionClass, ORM\Table::class);
        foreach ($refTables as $refTable) {
            if (null !== $refTable->name) {
                return $refTable->name;
            }
        }

        return self::normalizeClassName($className);
    }

    /**
     * 标准化类名
     */
    private static function normalizeClassName(string $className): string
    {
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

        if (null !== $id) {
            return "{$className}_{$id}";
        }

        return $className;
    }

    /**
     * 获取单个对象对应的缓存标签
     *
     * @param object $object 具有getId方法的对象
     */
    public static function getObjectTags(object $object): string
    {
        $className = get_class($object);
        $className = ClassUtils::getRealClass($className);

        if (!method_exists($object, 'getId')) {
            throw new \InvalidArgumentException('Object must have getId() method');
        }

        $id = $object->getId();

        if (null === $id) {
            return self::getClassTags($className);
        }

        if (!is_scalar($id)) {
            throw new \InvalidArgumentException('Entity ID must be scalar');
        }

        return self::getClassTags($className, (string) $id);
    }
}
