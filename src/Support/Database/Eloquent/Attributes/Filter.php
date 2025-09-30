<?php

declare(strict_types=1);

namespace Support\Database\Eloquent\Attributes;

use Attribute;
use Support\Database\Eloquent\Contracts\RequestFilter;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Filter implements RequestFilter
{
    public function __construct(
        public string $name,
    ) {}
}
