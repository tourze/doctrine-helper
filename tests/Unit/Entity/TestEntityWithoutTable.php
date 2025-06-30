<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity_without_table', options: ['comment' => 'Test Entity Without Table'])]
class TestEntityWithoutTable implements \Stringable
{
    private int $id = 2;

    public function getId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return 'TestEntityWithoutTable';
    }
} 