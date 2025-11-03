<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests\Fixtures\Model;

/**
 * 测试用：返回非标量 ID 的类
 */
class TestEntityWithNonScalarId
{
    /**
     * @return array<string, string>
     */
    public function getId(): array
    {
        return ['complex' => 'id'];
    }
}
