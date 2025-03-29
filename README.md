# Doctrine Helper

这个库提供了一系列实用的帮助函数，用于简化Doctrine ORM的使用。

## 功能

- `CacheHelper`: 提供缓存相关的辅助函数，如获取实体类对应的缓存标签
- `EntityDetector`: 用于检测一个类是否为Doctrine实体
- `ReflectionHelper`: 提供反射相关的辅助函数，简化Doctrine实体的属性和注解操作

## 安装

```bash
composer require tourze/doctrine-helper
```

## 使用示例

### CacheHelper

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

### EntityDetector

```php
use Tourze\DoctrineHelper\EntityDetector;

// 检查类是否为Doctrine实体
if (EntityDetector::isEntityClass(User::class)) {
    // 这是一个实体类
}
```

### ReflectionHelper

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

// 获取属性上的注解
$attributes = ReflectionHelper::getPropertyAttributes($reflection, SomeAttribute::class);
```

## 运行测试

本库使用PHPUnit进行单元测试。您可以通过以下方式运行测试：

```bash
composer test
```

要生成测试覆盖率报告，请运行：

```bash
composer test-coverage
```

这将在`coverage`目录中生成HTML格式的覆盖率报告。
