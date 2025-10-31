# Doctrine Helper

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-helper.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-helper)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
[![PHP Version Require](http://poser.pugx.org/tourze/doctrine-helper/require/php)](https://packagist.org/packages/tourze/doctrine-helper)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](https://github.com/tourze/php-monorepo)
[![Coverage Status](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](https://github.com/tourze/php-monorepo)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-helper.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-helper)

A lightweight helper library to simplify and optimize your usage of Doctrine ORM in PHP projects.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Advanced Usage](#advanced-usage)
- [API Reference](#api-reference)
- [Contributing](#contributing)
- [License](#license)

## Features

- **CacheHelper**: Generate cache IDs and tags for Doctrine entities and objects with table name support
- **EntityDetector**: Determine whether a class is a Doctrine entity using attributes
- **ReflectionHelper**: Simplify reflection operations for Doctrine entities, properties, and custom 
  attributes, with built-in caching for performance
- **SortableTrait**: Add sorting functionality to Doctrine entities with a simple trait

## Requirements

- PHP >= 8.1
- doctrine/orm >= 3.0
- doctrine/common >= 3.5
- doctrine/dbal >= 4.0

## Installation

```bash
composer require tourze/doctrine-helper
```

## Quick Start

### CacheHelper Example

```php
use Tourze\DoctrineHelper\CacheHelper;

// Get cache ID for a class
$cacheId = CacheHelper::getClassId(User::class);

// Get cache tags for a class
$tags = CacheHelper::getClassTags(User::class);

// Get cache tags for a class with ID
$tagsWithId = CacheHelper::getClassTags(User::class, '1');

// Get cache tags for an object
$objectTags = CacheHelper::getObjectTags($user);
```

### EntityDetector Example

```php
use Tourze\DoctrineHelper\EntityDetector;

// Check if a class is a Doctrine entity
if (EntityDetector::isEntityClass(User::class)) {
    // This is a Doctrine entity
}
```

### ReflectionHelper Example

```php
use Tourze\DoctrineHelper\ReflectionHelper;

// Get class reflection
$reflection = ReflectionHelper::getClassReflection(User::class);

// Get property reflection
$property = ReflectionHelper::getReflectionProperty($user, 'email');

// Get all properties
$properties = ReflectionHelper::getProperties(User::class);

// Get all methods
$methods = ReflectionHelper::getMethods(User::class);

// Get property attributes
$attributes = ReflectionHelper::getPropertyAttributes($reflection, SomeAttribute::class);

// Get class attributes
$classAttributes = ReflectionHelper::getClassAttributes($reflection, SomeAttribute::class);

// Check if class has specific attributes
$hasAttribute = ReflectionHelper::hasClassAttributes($reflection, SomeAttribute::class);
```

### SortableTrait Example

```php
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineHelper\SortableTrait;

#[ORM\Entity]
class Product
{
    use SortableTrait;
    
    // Your entity properties here
}

// Usage
$product = new Product();
$product->setSortNumber(10);
$sortNumber = $product->getSortNumber();
$sortableArray = $product->retrieveSortableArray();
```

## Advanced Usage

### Performance Features

- **Reflection caching**: All reflection operations are cached internally for better performance
- **Attribute reading**: Full support for PHP 8+ attributes
- **Memory efficient**: Optimized for minimal memory usage

### Working with Attributes

The ReflectionHelper provides powerful methods for working with PHP 8+ attributes:

```php
use Tourze\DoctrineHelper\ReflectionHelper;

$reflection = ReflectionHelper::getClassReflection(MyEntity::class);

// Get all instances of a specific attribute from properties
$attributes = ReflectionHelper::getPropertyAttributes($reflection, MyAttribute::class);

// Get first instance of a specific attribute from properties
$attribute = ReflectionHelper::getPropertyAttribute($reflection, MyAttribute::class);

// Iterate through class attributes
foreach (ReflectionHelper::getClassAttributes($reflection, MyAttribute::class) as $attr) {
    // Process each attribute instance
}

// Check if class has specific attributes
if (ReflectionHelper::hasClassAttributes($reflection, MyAttribute::class)) {
    // Handle accordingly
}
```

### Cache Management

CacheHelper provides flexible caching strategies:

```php
use Tourze\DoctrineHelper\CacheHelper;

// Generate stable cache keys
$key = CacheHelper::getClassId(User::class);  // Returns: 'User'

// Generate cache tags for invalidation
$tags = CacheHelper::getClassTags(User::class);       // Returns: ['User']
$tags = CacheHelper::getClassTags(User::class, '123'); // Returns: ['User', 'User:123']

// Get tags from entity instances
$user = new User();
$user->setId(456);
$tags = CacheHelper::getObjectTags($user);  // Returns: ['User', 'User:456']
```

## API Reference

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

## Contributing

- Issues and pull requests are welcome.
- Follow PSR-12 coding style.
- Ensure all PHPUnit tests pass before submitting.

## License

MIT License. See [LICENSE](LICENSE) for details.

## Changelog

See Git commit history for changes and releases.
