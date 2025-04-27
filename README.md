# Doctrine Helper

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-helper.svg)](https://packagist.org/packages/tourze/doctrine-helper)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A lightweight helper library to simplify and optimize your usage of Doctrine ORM in PHP projects.

## Features

- **CacheHelper**: Generate cache IDs and tags for Doctrine entities and objects.
- **EntityDetector**: Determine whether a class is a Doctrine entity.
- **ReflectionHelper**: Simplify reflection operations for Doctrine entities, properties, and custom attributes, with caching for performance.

## Installation

- Requires PHP >= 8.1
- Requires doctrine/orm >= 2.20 or >= 3.0
- Requires doctrine/common >= 3.5

Install via Composer:

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
$tagsWithId = CacheHelper::getClassTags(User::class, 1);

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
```

## Documentation

- See source code and unit tests for detailed API usage.
- Attribute and annotation reading supported.
- Reflection caching optimizes performance.

## Contributing

- Issues and pull requests are welcome.
- Follow PSR-12 coding style.
- Ensure all PHPUnit tests pass before submitting.

## License

MIT License. See [LICENSE](LICENSE) for details.

## Changelog

See Git commit history for changes and releases.
