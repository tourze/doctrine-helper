<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity_with_table', options: ['comment' => 'Test Entity With Table'])]
class TestEntityWithTable implements \Stringable
{
    private int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return 'TestEntityWithTable';
    }
} 