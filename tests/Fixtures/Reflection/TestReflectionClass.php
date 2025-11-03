<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests\Fixtures\Reflection;

/**
 * 测试用：抽象基类
 */
abstract class TestReflectionClass implements TestInterface
{
    public string $publicProperty = 'public';

    protected string $protectedProperty = 'protected';

    private string $privateProperty = 'private';

    public function publicMethod(): string
    {
        return 'public method';
    }

    protected function protectedMethod(): string
    {
        return 'protected method';
    }

    public function getPrivateProperty(): string
    {
        return $this->privateProperty;
    }
}
