<?php

declare(strict_types=1);

namespace Tourze\DoctrineHelper\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity_with_attributes')]
class TestEntityWithAttributes
{
    #[ORM\Column(type: 'string')]
    public string $name = '';

    #[ORM\Column(type: 'integer')]
    public int $age = 0;

    public string $noAttribute = '';
}
