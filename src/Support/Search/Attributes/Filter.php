<?php

declare(strict_types=1);

namespace Support\Search\Attributes;

use Attribute;
use Support\Search\Contracts;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Filter implements Contracts\Filter
{
    public function __construct(
        public string $name,
    ) {}
}
