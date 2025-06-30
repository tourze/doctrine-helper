<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mock_table', options: ['comment' => 'Mock Table'])]
class MockTable implements \Stringable
{
    public function __toString(): string
    {
        return 'MockTable';
    }
} 