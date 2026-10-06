<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Database;

use Illuminate\Database\Eloquent\Builder;
use Support\Search\Database\Provides\HasSort;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class HasSortWithoutSortable extends Builder
{
    use HasSort;
}
