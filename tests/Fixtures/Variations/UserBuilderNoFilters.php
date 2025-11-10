<?php

declare(strict_types=1);

namespace Tests\Fixtures\Variations;

use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;
use Tests\Fixtures\Role;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class UserBuilderNoTraitOrContract extends Builder implements Filterable
{
    use HasFilters;

    public function role(string|Role $role): static
    {
        return $this->where('role', $role);
    }

    public function ofStatus(string $status): static
    {
        return $this->where('status', $status);
    }

    public function isNew(): static
    {
        return $this->where('created_at', '>', now()->subDays(1));
    }
}
