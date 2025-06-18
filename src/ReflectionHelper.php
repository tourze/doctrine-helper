<?php

namespace Tourze\DoctrineHelper;

use Doctrine\Common\Util\ClassUtils;
use Traversable;

/**
 * 简化Reflection相关处理逻辑
 * 这个服务的东西通用型比较强的，所以没必要reset
 */
class ReflectionHelper
{
    private static array $cacheItems;

    private static function getOrSetCache(string $key, callable $callback): mixed
    {
        if (!isset(self::$cacheItems[$key])) {
            self::$cacheItems[$key] = $callback();
        }

        return self::$cacheItems[$key];
    }

    /**
     * 缓存一层，减少构建ReflectionClass的开销
     *
     * @deprecated 请使用`$this->entityManager->getClassMetadata($entity)->getReflectionClass();` 替代
     */
    public static function getClassReflection(object|string $object): \ReflectionClass
    {
        $className = is_object($object) ? ClassUtils::getClass($object) : strval($object);
        return self::$cacheItems['classReflection_' . md5($className)] ??= new \ReflectionClass($className);
    }

    public static function getReflectionProperty(object $object, string $propertyName): ?\ReflectionProperty
    {
        try {
            return static::getClassReflection($object)->getProperty($propertyName);
        } catch (\Throwable $exception) {
        }
        return null;
    }

    /**
     * @return array|\ReflectionProperty[]
     * @see https://www.php.net/manual/en/reflectionclass.getproperties.php
     */
    public static function getProperties(object|string $object, $filter = null): array
    {
        $className = is_object($object) ? ClassUtils::getClass($object) : strval($object);

        return self::getOrSetCache('classProperty_' . md5(serialize([$className, $filter])), function () use ($className, $filter) {
            $propertiesArray = [];

            $reflectionClass = static::getClassReflection($className);

            do {
                $properties = $reflectionClass->getProperties($filter);
                foreach ($properties as $property) {
                    $propertyName = $property->getName();
                    if (isset($propertiesArray[$propertyName])) {
                        continue;
                    }
                    $propertiesArray[$propertyName] = $property;
                }
            } while ($reflectionClass = $reflectionClass->getParentClass());

            return $propertiesArray;
        });
    }

    /**
     * @return array|\ReflectionProperty[]
     */
    public static function getMethods(object|string $object, $filter = null): array
    {
        $className = is_object($object) ? ClassUtils::getClass($object) : strval($object);
        return self::$cacheItems['classMethod_' . md5(serialize([$className, $filter]))] ??= static::getClassReflection($className)->getMethods($filter);
    }

    /**
     * 获取所有可能的类
     */
    public static function getParentClasses(\ReflectionClass $reflectionClass): array
    {
        $rs = [
            $reflectionClass->getName(),
        ];
        if ($reflectionClass->getParentClass()) {
            $rs = array_merge($rs, static::getParentClasses($reflectionClass->getParentClass()));
        }
        if (!empty($reflectionClass->getInterfaceNames())) {
            $rs = array_merge($rs, $reflectionClass->getInterfaceNames());
        }
        return array_values(array_unique($rs));
    }

    /**
     * @template T of object
     * @param \ReflectionClass $reflectionClass
     * @param class-string<T> $attributeName
     * @return array<T>
     */
    public static function getPropertyAttributes(\ReflectionClass $reflectionClass, string $attributeName): array
    {
        $rs = [];
        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes($attributeName) as $attribute) {
                $rs[] = $attribute->newInstance();
            }
        }
        return $rs;
    }

    /**
     * 有些情况，我们只需要读取一个
     *
     * @template T of object
     * @param \ReflectionClass $reflectionClass
     * @param class-string<T> $attributeName
     * @return T|object|null
     */
    public static function getPropertyAttribute(\ReflectionClass $reflectionClass, string $attributeName): ?object
    {
        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes($attributeName) as $attribute) {
                return $attribute->newInstance();
            }
        }
        return null;
    }

    /**
     * @template T of object
     * @param \ReflectionClass $reflectionClass
     * @param class-string<T> $attributeName
     * @return \Traversable<T>
     */
    public static function getClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): Traversable
    {
        foreach ($reflectionClass->getAttributes($attributeName) as $attribute) {
            /** @var \ReflectionAttribute<T> $attribute */
            yield $attribute->newInstance();
        }
    }

    public static function hasClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): bool
    {
        return !empty($reflectionClass->getAttributes($attributeName));
    }
}
