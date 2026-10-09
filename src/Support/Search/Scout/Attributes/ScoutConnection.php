<?php

declare(strict_types=1);

namespace Support\Search\Scout\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class ScoutConnection
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
