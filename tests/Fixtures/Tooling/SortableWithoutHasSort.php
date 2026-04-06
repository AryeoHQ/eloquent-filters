<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Sortable;
use Support\Primitives\Direction;
use Support\Primitives\Sort;
use Support\Primitives\Text;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class SortableWithoutHasSort extends Builder implements Sortable
{
    public function sort(null|string|Text|Sort $sort, null|string|Direction $direction = null): static
    {
        return $this;
    }
}
