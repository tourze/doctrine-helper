<?php

namespace Tourze\DoctrineHelper;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;

class EntityDetector
{
    /**
     * 判断是否是一个实体类
     */
    public static function isEntityClass(string $className): bool
    {
        $className = ClassUtils::getRealClass($className);
        if (class_exists($className) && ReflectionHelper::hasClassAttributes(ReflectionHelper::getClassReflection($className), ORM\Entity::class)) {
            return true;
        }
        return false;
    }
}
