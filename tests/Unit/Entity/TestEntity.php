<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity', options: ['comment' => 'Test Entity'])]
class TestEntity implements \Stringable
{
    public function __toString(): string
    {
        return 'TestEntity';
    }
} 