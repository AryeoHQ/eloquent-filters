<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class NonBuilderClass
{
    public function role(string $role): static
    {
        return $this;
    }
}
