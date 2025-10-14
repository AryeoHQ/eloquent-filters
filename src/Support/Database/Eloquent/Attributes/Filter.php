<?php

declare(strict_types=1);

namespace Support\Database\Eloquent\Attributes;

use Attribute;
use Support\Database\Eloquent\Contracts;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Filter implements Contracts\Filter
{
    public function __construct(
        public string $name,
    ) {}
}
