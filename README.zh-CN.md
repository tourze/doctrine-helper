# Doctrine Helper

这个库提供了一系列实用的帮助函数，用于简化 Doctrine ORM 的使用。

## 功能特性

- `CacheHelper`：提供缓存相关的辅助函数，如获取实体类对应的缓存标签。
- `EntityDetector`：用于检测一个类是否为 Doctrine 实体。
- `ReflectionHelper`：提供反射相关的辅助函数，简化 Doctrine 实体的属性和注解操作。

## 安装说明

- 依赖 PHP >= 8.1
- 依赖 doctrine/orm >= 2.20 或 >= 3.0
- 依赖 doctrine/common >= 3.5

通过 Composer 安装：

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
$tagsWithId = CacheHelper::getClassTags(User::class, 1);

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
```

## 详细文档

- 详细 API 请参考源码及测试用例
- 支持自定义属性与注解的读取
- 反射缓存机制优化性能

## 贡献指南

- 欢迎提交 Issue 和 PR
- 请遵循 PSR-12 代码规范
- 提交前请确保通过 PHPUnit 测试

## 版权和许可

- 开源协议：MIT
- 作者：tourze 团队

## 更新日志

- 详见项目的 Git 提交历史
