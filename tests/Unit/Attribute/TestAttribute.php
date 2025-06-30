<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY)]
class TestAttribute
{
    public function __construct(public string $value = '')
    {
    }
} 