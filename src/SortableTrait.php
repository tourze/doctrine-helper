<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * 排序功能 Trait
 *
 * 为实体提供排序功能的 Trait，包含排序号字段和相关操作方法。
 * 这是一个 library 包的公共 API，供外部项目使用。
 *
 * @phpstan-ignore-next-line
 */
trait SortableTrait
{
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['comment' => '排序号', 'default' => 0])]
    private int $sortNumber = 0;

    public function getSortNumber(): int
    {
        return $this->sortNumber;
    }

    public function setSortNumber(int $sortNumber): void
    {
        $this->sortNumber = $sortNumber;
    }

    /**
     * @return array<string, int>
     */
    public function retrieveSortableArray(): array
    {
        return [
            'sortNumber' => $this->getSortNumber(),
        ];
    }
}
