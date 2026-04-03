<?php

declare(strict_types=1);

namespace Support\Database\Eloquent\Contracts;

use Support\Primitives\Sort;

interface Sortable
{
    public function sort(null|Sort|string $sort): static;
}
