<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Database\Users\Builders;

use Illuminate\Database\Eloquent as Base;
use Support\Search\Attributes\Filter;
use Support\Search\Database\Contracts\Filterable;
use Support\Search\Database\Contracts\Sortable;
use Support\Search\Database\Provides\HasFilters;
use Support\Search\Database\Provides\HasSort;
use Tests\Fixtures\Support\Database\Users\Role;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Base\Builder<TModel>
 */
class Eloquent extends Base\Builder implements Filterable, Sortable
{
    use HasFilters;
    use HasSort;

    #[Filter('role')]
    public function role(string|Role $role): static
    {
        return $this->where('role', $role);
    }

    #[Filter('status')]
    public function ofStatus(string $status): static
    {
        return $this->where('status', $status);
    }

    #[Filter('is_new')]
    public function isNew(): static
    {
        return $this->where('created_at', '>', now()->subDays(1));
    }
}
