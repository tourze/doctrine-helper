<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests\Fixtures\Model;

/**
 * 测试用：带 getId()/setId() 的普通类
 */
class TestEntityWithId
{
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
