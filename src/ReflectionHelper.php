<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper;

use Doctrine\Common\Util\ClassUtils;

/**
 * 简化Reflection相关处理逻辑
 * 这个服务的东西通用型比较强的，所以没必要reset
 */
class ReflectionHelper
{
    /** @var array<string, mixed> */
    private static array $cacheItems = [];

    /**
     * 缓存一层，减少构建ReflectionClass的开销
     *
     * @deprecated 请使用`$this->entityManager->getClassMetadata($entity)->getReflectionClass();` 替代
     * @return \ReflectionClass<object>
     */
    public static function getClassReflection(object|string $object): \ReflectionClass
    {
        $className = is_object($object) ? ClassUtils::getClass($object) : strval($object);

        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class '{$className}' does not exist");
        }
        /** @phpstan-var class-string $className */
        $cacheKey = 'classReflection_' . md5($className);
        if (!isset(self::$cacheItems[$cacheKey])) {
            self::$cacheItems[$cacheKey] = new \ReflectionClass($className);
        }

        /** @var \ReflectionClass<object> */
        return self::$cacheItems[$cacheKey];
    }

    public static function getReflectionProperty(object $object, string $propertyName): ?\ReflectionProperty
    {
        try {
            $className = ClassUtils::getClass($object);
            $reflectionClass = new \ReflectionClass($className);

            return $reflectionClass->getProperty($propertyName);
        } catch (\Throwable $exception) {
        }

        return null;
    }

    /**
     * @see https://www.php.net/manual/en/reflectionclass.getproperties.php
     *
     * @return array<string, \ReflectionProperty>
     */
    public static function getProperties(object|string $object, ?int $filter = null): array
    {
        $className = is_object($object) ? ClassUtils::getClass($object) : strval($object);

        $cacheKey = 'classProperty_' . md5(serialize([$className, $filter]));
        if (!isset(self::$cacheItems[$cacheKey])) {
            self::$cacheItems[$cacheKey] = self::collectPropertiesFromClassHierarchy($className, $filter);
        }

        /** @var array<string, \ReflectionProperty> */
        return self::$cacheItems[$cacheKey];
    }

    /**
     * @return array<string, \ReflectionProperty>
     */
    private static function collectPropertiesFromClassHierarchy(string $className, ?int $filter): array
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class '{$className}' does not exist");
        }
        /** @phpstan-var class-string $className */

        /** @var array<string, \ReflectionProperty> $propertiesArray */
        $propertiesArray = [];
        $reflectionClass = new \ReflectionClass($className);

        do {
            $classProperties = self::getClassProperties($reflectionClass, $filter);
            foreach ($classProperties as $propertyName => $property) {
                if (!isset($propertiesArray[$propertyName])) {
                    $propertiesArray[$propertyName] = $property;
                }
            }
        } while ($reflectionClass = $reflectionClass->getParentClass());

        return $propertiesArray;
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @return array<string, \ReflectionProperty>
     */
    private static function getClassProperties(\ReflectionClass $reflectionClass, ?int $filter): array
    {
        $properties = $reflectionClass->getProperties($filter ?? \ReflectionProperty::IS_STATIC | \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        $result = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $result[$propertyName] = $property;
        }

        return $result;
    }

    /**
     * @return array<int, \ReflectionMethod>
     */
    public static function getMethods(object|string $object, ?int $filter = null): array
    {
        $className = is_object($object) ? ClassUtils::getClass($object) : strval($object);

        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class '{$className}' does not exist");
        }
        /** @phpstan-var class-string $className */
        $cacheKey = 'classMethod_' . md5(serialize([$className, $filter]));
        if (!isset(self::$cacheItems[$cacheKey])) {
            self::$cacheItems[$cacheKey] = (new \ReflectionClass($className))->getMethods($filter ?? \ReflectionMethod::IS_STATIC | \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE);
        }

        /** @var array<int, \ReflectionMethod> */
        return self::$cacheItems[$cacheKey];
    }

    /**
     * 获取所有可能的类
     *
     * @param \ReflectionClass<object> $reflectionClass
     * @return array<int, string>
     */
    public static function getParentClasses(\ReflectionClass $reflectionClass): array
    {
        $rs = [
            $reflectionClass->getName(),
        ];
        $parentClass = $reflectionClass->getParentClass();
        if (false !== $parentClass) {
            $rs = array_merge($rs, static::getParentClasses($parentClass));
        }
        $interfaceNames = $reflectionClass->getInterfaceNames();
        if (count($interfaceNames) > 0) {
            $rs = array_merge($rs, $interfaceNames);
        }

        return array_values(array_unique($rs));
    }

    /**
     * @template T of object
     *
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string<T> $attributeName
     *
     * @return array<T>
     */
    public static function getPropertyAttributes(\ReflectionClass $reflectionClass, string $attributeName): array
    {
        /** @var array<T> $rs */
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
     *
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string<T> $attributeName
     *
     * @return T|null
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
     *
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string<T> $attributeName
     *
     * @return \Traversable<T>
     */
    public static function getClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): \Traversable
    {
        foreach ($reflectionClass->getAttributes($attributeName) as $attribute) {
            /* @var \ReflectionAttribute<T> $attribute */
            yield $attribute->newInstance();
        }
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     */
    public static function hasClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): bool
    {
        return count($reflectionClass->getAttributes($attributeName)) > 0;
    }
}
