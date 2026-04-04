<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\Contracts\Sortable;
use Support\Database\Eloquent\HasFilters;
use Support\Database\Eloquent\HasSort;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class ValidBuilder extends Builder implements Filterable, Sortable
{
    use HasFilters;
    use HasSort;

    #[Filter('tag')]
    public function tags(string $tag): static
    {
        return $this->where('tag', $tag);
    }
}
