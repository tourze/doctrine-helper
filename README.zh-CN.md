# Doctrine Helper

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-helper.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-helper)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
[![PHP Version Require](http://poser.pugx.org/tourze/doctrine-helper/require/php)](https://packagist.org/packages/tourze/doctrine-helper)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](https://github.com/tourze/php-monorepo)
[![Coverage Status](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](https://github.com/tourze/php-monorepo)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-helper.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-helper)

一个轻量级的帮助类库，用于简化和优化您在 PHP 项目中使用 Doctrine ORM 的体验。

## 目录

- [功能特性](#功能特性)
- [系统要求](#系统要求)
- [安装说明](#安装说明)
- [快速开始](#快速开始)
- [高级用法](#高级用法)
- [API 参考](#api-参考)
- [贡献指南](#贡献指南)
- [版权和许可](#版权和许可)

## 功能特性

- **CacheHelper**：为 Doctrine 实体和对象生成缓存 ID 和标签，支持数据表名称
- **EntityDetector**：使用属性来判断一个类是否为 Doctrine 实体
- **ReflectionHelper**：简化 Doctrine 实体、属性和自定义属性的反射操作，
  内置缓存以提升性能
- **SortableTrait**：通过简单的 trait 为 Doctrine 实体添加排序功能

## 系统要求

- PHP >= 8.1
- doctrine/orm >= 3.0
- doctrine/common >= 3.5
- doctrine/dbal >= 4.0

## 安装说明

```bash
composer require tourze/doctrine-helper
```

## 快速开始

### CacheHelper 示例

```php
use Tourze\DoctrineHelper\CacheHelper;

// 获取类名对应的缓存ID
$cacheId = CacheHelper::getClassId(User::class);

// 获取类名的缓存标签
$tags = CacheHelper::getClassTags(User::class);

// 带ID的缓存标签
$tagsWithId = CacheHelper::getClassTags(User::class, '1');

// 获取对象的缓存标签
$objectTags = CacheHelper::getObjectTags($user);
```

### EntityDetector 示例

```php
use Tourze\DoctrineHelper\EntityDetector;

// 检查类是否为 Doctrine 实体
if (EntityDetector::isEntityClass(User::class)) {
    // 这是一个实体类
}
```

### ReflectionHelper 示例

```php
use Tourze\DoctrineHelper\ReflectionHelper;

// 获取类的反射对象
$reflection = ReflectionHelper::getClassReflection(User::class);

// 获取属性的反射对象
$property = ReflectionHelper::getReflectionProperty($user, 'email');

// 获取所有属性
$properties = ReflectionHelper::getProperties(User::class);

// 获取所有方法
$methods = ReflectionHelper::getMethods(User::class);

// 获取属性的属性
$attributes = ReflectionHelper::getPropertyAttributes($reflection, SomeAttribute::class);

// 获取类属性
$classAttributes = ReflectionHelper::getClassAttributes($reflection, SomeAttribute::class);

// 检查类是否具有特定属性
$hasAttribute = ReflectionHelper::hasClassAttributes($reflection, SomeAttribute::class);
```

### SortableTrait 示例

```php
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineHelper\SortableTrait;

#[ORM\Entity]
class Product
{
    use SortableTrait;
    
    // 您的实体属性
}

// 使用方法
$product = new Product();
$product->setSortNumber(10);
$sortNumber = $product->getSortNumber();
$sortableArray = $product->retrieveSortableArray();
```

## 高级用法

### 性能特性

- **反射缓存**：所有反射操作都在内部进行缓存，提升性能
- **属性读取**：完全支持 PHP 8+ 属性
- **内存高效**：优化了内存使用量

### 属性操作

ReflectionHelper 提供了强大的 PHP 8+ 属性操作方法：

```php
use Tourze\DoctrineHelper\ReflectionHelper;

$reflection = ReflectionHelper::getClassReflection(MyEntity::class);

// 获取属性上特定属性的所有实例
$attributes = ReflectionHelper::getPropertyAttributes($reflection, MyAttribute::class);

// 获取属性上特定属性的第一个实例
$attribute = ReflectionHelper::getPropertyAttribute($reflection, MyAttribute::class);

// 遍历类属性
foreach (ReflectionHelper::getClassAttributes($reflection, MyAttribute::class) as $attr) {
    // 处理每个属性实例
}

// 检查类是否具有特定属性
if (ReflectionHelper::hasClassAttributes($reflection, MyAttribute::class)) {
    // 相应处理
}
```

### 缓存管理

CacheHelper 提供灵活的缓存策略：

```php
use Tourze\DoctrineHelper\CacheHelper;

// 生成稳定的缓存键
$key = CacheHelper::getClassId(User::class);  // 返回: 'User'

// 生成用于失效的缓存标签
$tags = CacheHelper::getClassTags(User::class);       // 返回: ['User']
$tags = CacheHelper::getClassTags(User::class, '123'); // 返回: ['User', 'User:123']

// 从实体实例获取标签
$user = new User();
$user->setId(456);
$tags = CacheHelper::getObjectTags($user);  // 返回: ['User', 'User:456']
```

## API 参考

### CacheHelper

- `getClassId(string $className): string`
- `getClassTags(string $className, string $id = null): array`
- `getObjectTags(object $object): array`

### EntityDetector

- `isEntityClass(string $className): bool`

### ReflectionHelper

- `getClassReflection(object|string $object): \ReflectionClass`
- `getReflectionProperty(object $object, string $propertyName): ?\ReflectionProperty`
- `getProperties(object|string $object, $filter = null): array`
- `getMethods(object|string $object, $filter = null): array`
- `getParentClasses(\ReflectionClass $reflectionClass): array`
- `getPropertyAttributes(\ReflectionClass $reflectionClass, string $attributeName): array`
- `getPropertyAttribute(\ReflectionClass $reflectionClass, string $attributeName): ?object`
- `getClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): \Traversable`
- `hasClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): bool`

### SortableTrait

- `setSortNumber(int $sortNumber): self`
- `getSortNumber(): int`
- `retrieveSortableArray(): array`

## 贡献指南

- 欢迎提交 Issue 和 PR
- 请遵循 PSR-12 代码规范
- 提交前请确保通过 PHPUnit 测试

## 版权和许可

MIT License. 详见 [LICENSE](LICENSE) 文件。

## 更新日志

详见项目的 Git 提交历史。
