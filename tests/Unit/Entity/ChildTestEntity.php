<?php

namespace Tourze\DoctrineHelper\Tests\Unit\Entity;

class ChildTestEntity extends TestReflectionEntity
{
    private string $childProperty = 'child';

    public function getChildProperty(): string
    {
        return $this->childProperty;
    }
} 