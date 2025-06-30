<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_reflection_entity', options: ['comment' => 'Test Reflection Entity'])]
class TestReflectionEntity implements \Stringable
{
    private int $id = 1;
    protected string $name = 'test';
    public array $data = [];

    public function getId(): int
    {
        return $this->id;
    }

    // 添加一个方法，以便测试
    public function someMethod(): void
    {
    }

    public function __toString(): string
    {
        return 'TestReflectionEntity';
    }
} 