<?php

declare(strict_types=1);

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
        if (!class_exists($className)) {
            return false;
        }

        $className = ClassUtils::getRealClass($className);
        $reflectionClass = new \ReflectionClass($className);
        if (ReflectionHelper::hasClassAttributes($reflectionClass, ORM\Entity::class)) {
            return true;
        }

        return false;
    }
}
