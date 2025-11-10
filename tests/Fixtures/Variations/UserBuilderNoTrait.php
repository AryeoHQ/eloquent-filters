<?php

declare(strict_types=1);

namespace Tests\Fixtures\Variations;

use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\Filterable;
use Tests\Fixtures\Role;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class UserBuilderNoTrait extends Builder implements Filterable
{
    public function filter(array $requestParams): static
    {
        return $this;
    }

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
