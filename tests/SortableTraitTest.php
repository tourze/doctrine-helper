<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineHelper\SortableTrait;

/**
 * @internal
 */
#[CoversClass(SortableTrait::class)]
final class SortableTraitTest extends TestCase
{
    private TestSortableEntity $entity;

    protected function setUp(): void
    {
        $this->entity = new TestSortableEntity();
    }

    public function testDefaultSortNumber(): void
    {
        // 测试默认值为0
        $this->assertSame(0, $this->entity->getSortNumber());
    }

    public function testSetSortNumber(): void
    {
        // 测试setter方法
        $this->entity->setSortNumber(10);
        $this->assertSame(10, $this->entity->getSortNumber());
    }

    public function testSetSortNumberWithNegativeValue(): void
    {
        // 测试负数值
        $this->entity->setSortNumber(-5);
        $this->assertSame(-5, $this->entity->getSortNumber());
    }

    public function testRetrieveSortableArray(): void
    {
        // 测试retrieveSortableArray方法返回默认值
        $result = $this->entity->retrieveSortableArray();
        $expected = ['sortNumber' => 0];
        $this->assertSame($expected, $result);
    }

    public function testRetrieveSortableArrayWithCustomValue(): void
    {
        // 测试retrieveSortableArray方法返回自定义值
        $this->entity->setSortNumber(25);
        $result = $this->entity->retrieveSortableArray();
        $expected = ['sortNumber' => 25];
        $this->assertSame($expected, $result);
    }
}
