<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


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
